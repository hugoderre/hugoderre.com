<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminTagController extends AdminItemController
{

    #[Route('/admin/tags/create', name: 'admin_tag_create')]
    // #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, FormFactoryInterface $formFactoryInterface, EntityManagerInterface $entityManager): Response
    {
        if(!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/login');
        }

        // $tag = new Tag();

        // $builder = $formFactoryInterface->createBuilder(TagType::class, $tag);

        // $form = $builder->getForm();
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $tag = $form->getData();
        //     $entityManager->persist($tag);
        //     $entityManager->flush();
        //     return $this->redirectToRoute('blog');
        // }

        // return $this->renderForm('admin/tag/create.html.twig', [
        //     'form' => $form,
        //     'page' => 'admin-tag-create',
        //     'admin' => true,
        // ]);
    }

    #[Route('/admin/tags/delete/{id}', name: 'admin_tag_delete')]
    public function tagDelete(int $id, EntityManagerInterface $entityManager): Response
    {
        return $this->delete($id, $entityManager, Tag::class, 'admin_tag_list');
    }

    #[Route('/admin/tags', name: 'admin_tag_list')]
    public function tagList(EntityManagerInterface $entityManager): Response
    {
        return $this->list($entityManager, Tag::class);
    }

}
