<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private const NB_ARTICLES = 40;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('zh_TW');

        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $article = new Article();
            $article->setTitle($faker->realText(15));
            $article->setDescription($faker->text(150));
            $article->setContent($faker->realTextBetween(400, 750));
            $article->setCreatedAt($faker->dateTimeBetween('-2 years'));
            $article->setVisible($faker->boolean(80));

            $manager->persist($article);
        }

        $manager->flush();
    }
}
