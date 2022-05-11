<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\TestsProvider\Entity\PostProvider;
use App\TestsProvider\Entity\UserProvider;

final class PostTest extends TestCase {
    public function testAttachAuthorToPost() {
        $post = PostProvider::getPost();
        $user = UserProvider::getRegularUser();
        $post->setAuthor($user);
        $this->assertTrue($post->getAuthor() === $user);
    }

    public function testAttachAuthorToPostWithAdminRole() {
        $post = PostProvider::getPost();
        $user = UserProvider::getAdminUser();
        $post->setAuthor($user);
        $this->assertTrue($post->getAuthor() === $user);
    }
}