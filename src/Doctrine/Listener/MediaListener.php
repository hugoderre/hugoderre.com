<?php

namespace App\Doctrine\Listener;

use App\Entity\Media;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Security;

class MediaListener
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Media $media)
    {
        if(empty($media->getImageUploadedAt())) {
            $media->setImageUploadedAt(new DateTimeImmutable());
        }

        if(empty($media->getAuthor())) {
            $media->setAuthor($this->security->getUser());
        }
    }
}
