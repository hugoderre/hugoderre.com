<?php

namespace App\Controller;

use App\Entity\Post;
use DateTimeImmutable;
use Doctrine\ORM\Repository\RepositoryFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function list(): Response
    {
        return $this->render('blog/list.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    #[Route('/blog/create', name: 'create')]
    // #[ParamConverter('post')]
    public function create(Request $request): Response
    {
        
        $post = new Post();

        $post->setTitle('Hello world')
             ->setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas veritatis mollitia incidunt sint, quae dolore quidem, explicabo itaque aperiam reiciendis dicta quia tenetur placeat, nemo rerum? Excepturi officiis consequatur nihil.')
             ->setThumbnail('https://picsum.photos/200/300')
             ->setCreatedAt(new DateTimeImmutable())
             ->setIsPublished(true);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($post);
        $manager->flush();

        return $this->render('');

    }

    #[Route('/blog', name: 'blog')]
    // #[ParamConverter('post')]
    public function all(): Response
    {
        
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        return $this->render('blog/blog.html.twig', [
            'controller_name'   => 'BlogController',
            'posts'             => $posts,
        ]);

    }

    
}
?>

