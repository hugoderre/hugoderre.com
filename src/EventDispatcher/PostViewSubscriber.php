<?php

namespace App\EventDispatcher;

use App\Event\PostViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class PostViewSubscriber implements EventSubscriberInterface
{
    private $logger;
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'post.view' => 'sendMailToAdmin'
        ];
    }

    public function sendMailToAdmin(PostViewEvent $post) {
        $message = 'L\'article "' . $post->getPost()->getTitle() . '" vient d\'être visité';
        $this->logger->info($message);
    }
}