<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\TestsProvider\Entity\PostProvider;
use App\TestsProvider\Entity\TagProvider;

final class TagTest extends TestCase {
    public function testAttachPostToTag() {
        $tag = TagProvider::getTag();
        $post = PostProvider::getPost();
        $tag->addPost($post);
        $this->assertTrue($tag->getPosts()->contains($post));
    }
}