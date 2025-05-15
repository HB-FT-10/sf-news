<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private const NB_ARTICLES = 40;
    private const NB_USERS = 5;
    private const CATEGORY_NAMES = ['PHP', 'Symfony', 'Rust', 'Typescript', 'Angular', 'Javascript'];

    public function __construct(
        private string $lang
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create($this->lang);

        // --- CATEGORIES -------------------------------------------------------------------
        $categories = [];

        foreach (self::CATEGORY_NAMES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $categories[] = $category;
        }

        // --- USERS -----------------------------------------------------------------------
        $users = [];

        for ($i = 0; $i < self::NB_USERS; $i++) {
            $user = new User();
            $user
                ->setEmail("regular$i@user.com")
                ->setPassword('test');

            $manager->persist($user);

            $users[] = $user;
        }

        $admin = new User();
        $admin
            ->setEmail("admin@user.com")
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('admin');

        $manager->persist($admin);
        $users[] = $admin;

        // --- ARTICLES ---------------------------------------------------------------------
        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $article = new Article();
            $article
            ->setTitle($faker->realText(15))
            ->setDescription($faker->text(150))
            ->setContent($faker->realTextBetween(400, 750))
            // ->setCreatedAt($faker->dateTimeBetween('-2 years'))
            ->setVisible($faker->boolean(80))
            ->setCategory($faker->randomElement($categories))
            ->setAuthor($faker->randomElement($users));

            $manager->persist($article);
        }

        // --- API TOKEN -----------------------------------------------------
        $token = new ApiToken();
        $token->setToken('cyCRJNMUBrXLTTBjBjSZUglxwbQjcPRtBELLzV');

        $manager->persist($token);

        $manager->flush();
    }
}
