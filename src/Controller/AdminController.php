<?php

namespace App\Controller;

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

class AdminController extends AbstractController
{
    #[Route('/admin/post/create', name: 'admin_create_post')]
    // #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, FormFactoryInterface $formFactoryInterface, EntityManagerInterface $entityManager): Response
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
        ]);
    }
}
