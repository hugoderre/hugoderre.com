<?php

namespace App\DataFixtures;

use App\Entity\Post;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Loremizer\loremizer;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        // create 20 posts! Bam!
        for ($i = 0; $i < 20; $i++) {
            $product = new Post();
            $product->setTitle(loremizer::getTitle());
            $product->setThumbnail(loremizer::getImg());
            $product->setContent(loremizer::getPhrase(5)); 
            $product->setExcerpt(loremizer::getPhrase(2));
            $product->setCreatedAt(new DateTimeImmutable());
            $product->setIsPublished(true);
            $manager->persist($product);
        }

        $manager->flush();
    }
}

