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

class AdminPostController extends AdminItemController
{

    #[Route('/admin/posts/create', name: 'admin_post_create')]
    // #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, FormFactoryInterface $formFactoryInterface, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/login');
        }

        $post = new Post();
        $post->setThumbnail('https://picsum.photos/800/500')
            ->setCreatedAt(new DateTimeImmutable())
            ->setStatus('draft');

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

    #[Route('/admin/posts/delete/{id}', name: 'admin_post_delete')]
    public function postDelete(int $id, EntityManagerInterface $entityManager): Response
    {
        return $this->delete($id, $entityManager, Post::class, 'admin_post_list');
    }

    #[Route('/admin/posts', name: 'admin_post_list')]
    public function postList(EntityManagerInterface $entityManager): Response
    {
        return $this->list($entityManager, Post::class);
    }
}
