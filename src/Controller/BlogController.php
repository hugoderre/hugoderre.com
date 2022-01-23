<?php

namespace App\Controller;

use App\Entity\Post;
use App\Event\PostViewEvent;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    #[Route('/blog', name: 'blog')]
    // #[ParamConverter('post')]
    public function all(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        
        return $this->render('blog/blog.html.twig', [
            'posts'             => $posts,
        ]);
        
    }

    #[Route('/blog/{slug}', name: 'post')]
    // #[ParamConverter('post')]
    public function post($slug, Post $post, EventDispatcherInterface $dispatcher): Response
    {
        $postViewEvent = new PostViewEvent($post);
        $dispatcher->dispatch($postViewEvent, 'post.view');

        return $this->render('blog/single.html.twig', [
            'post' => $post
        ]);
    }
}
?>

