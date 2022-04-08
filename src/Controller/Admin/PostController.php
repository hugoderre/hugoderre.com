<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use DateTimeImmutable;
use App\Form\Type\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/admin/posts', name: 'admin_post_list')]
    public function postList(Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/login');
        }

        $posts = $entityManager->getRepository(Post::class)->findAll();

        return $this->render('admin/post/list.html.twig', [
            'posts' => $posts,
            'page'  => 'admin-post-list',
        ]);
    }

    #[Route('/admin/posts/create', name: 'admin_create_post')]
    // #[IsGranted('ROLE_ADMIN')]
    public function postCreate(Request $request, FormFactoryInterface $formFactoryInterface, EntityManagerInterface $entityManager): Response
    {
        if(!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/login');
        }

        $post = new Post();
        $post->setThumbnail('https://picsum.photos/800/500')
            ->setCreatedAt(new DateTimeImmutable())
            ->setIsPublished(true);

        $builder = $formFactoryInterface->createBuilder(PostType::class, $post);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('blog');
        }

        return $this->renderForm('admin/post/create.html.twig', [
            'form' => $form,
            'page' => 'admin-post-create',
            'admin' => true,
        ]);
    }
}
