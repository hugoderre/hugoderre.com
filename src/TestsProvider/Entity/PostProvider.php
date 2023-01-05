<?php

namespace App\TestsProvider\Entity;

use App\Entity\PostType\Post;

class PostProvider { 
    public static function getPost(): Post {
        $post = new Post();
        $post->setTitle('test');
        $post->setContent('test');
        return $post;
    }
}