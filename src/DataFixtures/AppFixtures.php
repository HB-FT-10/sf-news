<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const NB_ARTICLES = 40;
    private const CATEGORY_NAMES = ['PHP', 'Symfony', 'Rust', 'Typescript', 'Angular', 'Javascript'];

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('zh_TW');

        // --- CATEGORIES -------------------------------------------------------------------
        $categories = [];

        foreach (self::CATEGORY_NAMES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $categories[] = $category;
        }

        // --- ARTICLES ---------------------------------------------------------------------
        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $article = new Article();
            $article
            ->setTitle($faker->realText(15))
            ->setDescription($faker->text(150))
            ->setContent($faker->realTextBetween(400, 750))
            ->setCreatedAt($faker->dateTimeBetween('-2 years'))
            ->setVisible($faker->boolean(80))
            ->setCategory($faker->randomElement($categories));

            $manager->persist($article);
        }

        // --- USERS -----------------------------------------------------------------------
        $user = new User();
        $user
            ->setEmail("regular@user.com")
            ->setPassword($this->hasher->hashPassword($user, 'test'));

        $manager->persist($user);

        $admin = new User();
        $admin
            ->setEmail("admin@user.com")
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($user, 'admin'));

        $manager->persist($admin);

        $manager->flush();
    }
}
