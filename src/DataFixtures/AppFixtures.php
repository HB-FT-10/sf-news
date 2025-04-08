<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private const NB_ARTICLES = 40;
    private const CATEGORY_NAMES = ['PHP', 'Symfony', 'Rust', 'Typescript', 'Angular', 'Javascript'];

    public function load(ObjectManager $manager): void
    {
        $categories = [];

        foreach (self::CATEGORY_NAMES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $categories[] = $category;
        }

        $faker = Factory::create('zh_TW');

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

        $manager->flush();
    }
}
