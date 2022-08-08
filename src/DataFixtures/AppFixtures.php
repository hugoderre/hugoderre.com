<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Loremizer\loremizer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $hasher)
    {
        $this->slugger  = $slugger;
        $this->hasher   = $hasher;
        $this->faker    = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->users();
        $this->tags();
        $this->postsAndComments();
        $this->manager->flush();
    }

    private function tags() {
        $this->wordpressTag = new Tag();
        $this->wordpressTag->setName('WordPress');
        $this->wordpressTag->setColor('#00749C');
        $this->manager->persist($this->wordpressTag);

        $this->phpTag = new Tag();
        $this->phpTag->setName('PHP');
        $this->phpTag->setColor('#474A8A');
        $this->manager->persist($this->phpTag);

        $this->symfonyTag = new Tag();
        $this->symfonyTag->setName('Symfony');
        $this->symfonyTag->setColor('#FFF');
        $this->manager->persist($this->symfonyTag);
    }

    private function postsAndComments()
    {
        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->setTitle(loremizer::getTitle());
            $post->setThumbnail('https://picsum.photos/id/' . intval($i + 20) . '/800/500');
            $post->setContent(loremizer::getPhrase(5));
            $post->setExcerpt(loremizer::getPhrase(2));
            $post->addTag($this->wordpressTag);
            $post->addTag($this->phpTag);
            $post->addTag($this->symfonyTag);
            $post->setStatus(Post::STATUS_PUBLISH);
            $post->setAuthor($this->userAdmin);

            for ($j = 0; $j < rand(3, 5); $j++) {
                $comment = new Comment();
                $comment->setContent(loremizer::getPhrase(5));
                $comment->setAuthorName($this->faker->name);
                $comment->setAuthorEmail($this->faker->email);
                $comment->setAuthorWebsite($this->faker->url);
                $comment->setStatus(Comment::STATUS_APPROVED);
                $comment->setCreatedAt(new DateTimeImmutable());
                $comment->setClientIp($this->faker->ipv4);
                $comment->setUserAgent($this->faker->userAgent);
                $this->manager->persist($comment);
                $post->addComment($comment);
            }

            $this->manager->persist($post);
        }
    }

    private function users()
    {
        $this->userAdmin = new User();

        $hash = $this->hasher->hashPassword($this->userAdmin, 'password');

        $this->userAdmin->setUsername('admin');
        $this->userAdmin->setEmail($this->faker->email());
        $this->userAdmin->setFirstName('Hugo');
        $this->userAdmin->setLastName('Derré');
        $this->userAdmin->setPassword($hash);
        $this->userAdmin->setRoles(['ROLE_ADMIN']);
        $this->userAdmin->setIsVerified(true);
        $this->manager->persist($this->userAdmin);

        for ($i = 0; $i < 20; $i++) {
            $user = new User();

            $hash = $this->hasher->hashPassword($user, 'password');

            $user->setUsername($this->faker->userName());
            $user->setEmail($this->faker->email());
            $user->setFirstName($this->faker->firstName());
            $user->setLastName($this->faker->lastName());
            $user->setPassword($hash);
            $user->setIsVerified(true);
            $this->manager->persist($user);
        }
    }
}
