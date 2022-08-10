<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\Post;
use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Loremizer\loremizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
		$this->medias();
        $this->postsAndComments();
		$this->projects();
        $this->manager->flush();
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

	private function medias() {
		$this->media = new Media();
		$this->media->setTitle('media1');
		$uploadedFile = new UploadedFile(
			'/var/www/src/DataFixtures/test.jpg',
			'test.jpg',
			'image/jpeg',
			null,
			true
		);
		$this->media->setFile($uploadedFile);
		$this->media->setFileName('test.jpg');
		$this->media->setSize($uploadedFile->getSize());
		$this->media->setAlt('alt');
		$this->media->setAuthor($this->userAdmin);
		$this->media->setUploadedAt(new DateTimeImmutable());
		$this->manager->persist($this->media);
	}

    private function postsAndComments()
    {
        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->setTitle(loremizer::getTitle());
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
				$comment->setSpamScore(rand(0, 1));
                $comment->setClientIp($this->faker->ipv4);
                $comment->setUserAgent($this->faker->userAgent);
                $this->manager->persist($comment);
                $post->addComment($comment);
            }

            $this->manager->persist($post);
        }
    }

	private function projects() {
		$this->project = new Project();
		$this->project->setName($this->faker->title);
		$this->project->setSlug($this->faker->title);
		$this->project->setThumbnail($this->media);
		$this->project->setDescription(loremizer::getPhrase(5));
		$this->project->setStatus(Project::STATUS_PUBLISH);
		$this->project->addGallery($this->media);
		$this->manager->persist($this->project);
	}
}
