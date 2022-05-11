<?php

namespace App\TestsProvider\Entity;

use App\Entity\Tag;

class TagProvider { 
    public static function getTag(): Tag {
        $tag = new Tag();
        $tag->setName('test');
        return $tag;
    }
}