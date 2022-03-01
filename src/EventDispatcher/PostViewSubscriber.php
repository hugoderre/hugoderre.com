<?php

namespace App\EventDispatcher;

use App\Event\PostViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class PostViewSubscriber implements EventSubscriberInterface
{
    private $logger;
    private $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            // 'post.view' => 'sendMailToAdmin'
        ];
    }

    public function sendMailToAdmin(PostViewEvent $post)
    {
        $email = new TemplatedEmail();
        $email->from(new Address('hdv2symfapp@yopmail.com', 'Hugo Derre Info'))
            ->to('hugo.d83@outlook.fr')
            ->text("Un visiteur est en train de voir la page '" . $post->getPost()->getTitle() . "'")
            ->htmlTemplate('mails/post-view.html.twig')
            ->context([
                'post' => $post->getPost()
            ])
            ->subject("Visite du post " . $post->getPost()->getTitle() . "'");

        $this->mailer->send($email);
    }
}
