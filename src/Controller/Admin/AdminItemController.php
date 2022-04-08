<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class AdminItemController extends AbstractController
{
    abstract public function create(Request $request, FormFactoryInterface $formFactoryInterface, EntityManagerInterface $entityManager): Response;

    public function delete(int $id, EntityManagerInterface $entityManager, string $entityClass, string $redirectRouteName): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/login');
        }

        $post = $entityManager->getRepository($entityClass)->find($id);

        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute($redirectRouteName);
    }

    public function list(EntityManagerInterface $entityManager, string $entityClass): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/login');
        }

        $items = $entityManager->getRepository($entityClass)->findAll();

        // Each admin item twig template must be in a folder with the same name as the entity class
        $templateFolder = explode('\\',$entityClass);
        $templateFolder = strtolower(array_pop($templateFolder));
        $twigTemplatePath = $this->get('twig')->getLoader()->getPaths()[0] . '/admin/' . $templateFolder . '/list.html.twig';
        if (!file_exists($twigTemplatePath)) {
            throw new FileNotFoundException('Template file not found: ' . $twigTemplatePath);
        }

        return $this->render("admin/$templateFolder/list.html.twig", [
            'items' => $items,
        ]);
    }
}
