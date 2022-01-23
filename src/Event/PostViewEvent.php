<?php

namespace App\Event;

use App\Entity\Post as EntityPost;
use Symfony\Contracts\EventDispatcher\Event;

class PostViewEvent extends Event
{
    private $post;

    public function __construct(EntityPost $post)
    {
        $this->post = $post;
    }

    public function getPost() {
        return $this->post;
    }
}
