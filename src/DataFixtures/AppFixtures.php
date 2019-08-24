<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');
        //création de 3 catégories fake
        for($i = 1; $i <= 3; $i++){
            $category = new Category();
            $category->setName($faker->sentence());
            $manager->persist($category);

            for($j = 1; $j <= mt_rand(4,6); $j++){
                $article = new Article();
                $content = '<p>'.join($faker->paragraphs(5),'</p><p>').'</p>';
                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);

                $manager->persist($article);

                for($k=0; $k <= mt_rand(4,10); $k++){
                    $comment = new Comments();
                    $content = '<p>'.join($faker->paragraphs(2),'</p><p>').'</p>';
                    $days = (new \DateTime())->diff($article->getCreatedAt())->days;

                    $comment->setAuthor($faker->name)
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween('-'.$days.' days'))
                        ->setArticle($article);

                    $manager->persist($comment);



                }

            }

        }
        $manager->flush();
    }
}
