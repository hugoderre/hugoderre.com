<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;

abstract class AdminItemController extends AbstractController
{
    public function deleteItem(int $id, EntityManagerInterface $entityManager, string $entityClass, string $redirectRouteName): Response
    {
        $item = $entityManager->getRepository($entityClass)->find($id);

        $entityManager->remove($item);
        $entityManager->flush();

        return $this->redirectToRoute($redirectRouteName);
    }

    public function renderList($items, string $entityClass, Request $request, $extraTwigParams = []): Response
    {
        // Each admin item twig template must be in a folder with the same name as the entity class
        $templateFolder = explode('\\', $entityClass);
        $templateFolder = strtolower(array_pop($templateFolder));
        $twigTemplatePath = $this->get('twig')->getLoader()->getPaths()[0] . '/admin/' . $templateFolder . '/list.html.twig';
        if (!file_exists($twigTemplatePath)) {
            throw new FileNotFoundException('Template file not found: ' . $twigTemplatePath);
        }

        return $this->render("admin/$templateFolder/list.html.twig", [
            'items' => $items,
            'query' => $request->query->all(),
        ] + $extraTwigParams);
    }
}
