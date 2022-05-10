<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Entity\Post;
use App\Entity\User;

final class PostTest extends TestCase {
    public function testNewPost() {
        $post = new Post();
        $post->setTitle('test');
        $post->setContent('test');
        $post->setAuthor(new User());
        $this->assertTrue($post->getTitle() === 'test');
        $this->assertTrue($post->getContent() === 'test');
        $this->assertTrue($post->getAuthor() instanceof User);
    }
}