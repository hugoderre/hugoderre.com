<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use DateTimeImmutable;
use App\Form\Type\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;

class AdminPostController extends AdminItemController
{
    #[Route('/admin/posts/create', name: 'admin_post_create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, FormFactoryInterface $formFactoryInterface, EntityManagerInterface $entityManager): Response
    {
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
    // #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        if(!$post = $entityManager->getRepository(Post::class)->find($id)) {
            throw $this->createNotFoundException('Post not found');
        }

        if($post->getStatus() !== Post::STATUS_TRASH) {
            $post->setStatus(Post::STATUS_TRASH);
            $entityManager->flush();
            return $this->redirectToRoute('admin_post_list');
        }

        return $this->deleteItem(
            $id, 
            $entityManager, 
            Post::class, 
            'admin_post_list',
            ['status' => 'trash']
        );
    }

    #[Route('/admin/posts', name: 'admin_post_list')]
    #[IsGranted('ROLE_ADMIN')]
    public function list(Request $request, PostRepository $postRepository): Response
    {
        if(!$request->query->get('status')) {
            $request->query->set('status', Post::STATUS_PUBLISH);
        }

        $fields = [];
        if($search = $request->query->get('s')) {
            $fields['title'] = ['value' => '%' . $search . '%', 'operator' => 'LIKE'];
        }
        if($status = $request->query->get('status')) {
            $fields['status'] = ['value' => $status];
        }

        return $this->renderList(
            $postRepository->findByFields($fields, 'ASC'), 
            Post::class, 
            $request,
            ['statuses' => Post::getStatusList()]
        );
    }
}
