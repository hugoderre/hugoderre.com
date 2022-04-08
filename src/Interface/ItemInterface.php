<?php

namespace App\Interface;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ItemInterface {
    public function list(EntityManagerInterface $entityManager): Response;
    public function create(Request $request, FormFactoryInterface $formFactoryInterface, EntityManagerInterface $entityManager): Response;
    public function delete(int $id, EntityManagerInterface $entityManager): Response;
}