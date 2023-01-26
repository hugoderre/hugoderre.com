<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\PostType\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Form\Service\CommentService;
use App\Trait\LocaleTrait;
use App\Trait\PostTypeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
	use PostTypeTrait;
	use LocaleTrait;

    #[Route('/blog', name: 'blog')]
    public function blog(PostRepository $postRepository, Request $request): Response
    {
        $posts = $postRepository->findBy([
			'status' => 'publish', 
			'lang' => $request->getLocale()
		], [
			'publishedAt' => 'DESC'
		]);

        return $this->render('blog/blog.html.twig', [
            'posts' => $posts,
            'page'  => 'blog',
        ]);
    }

    #[Route('/blog/{slug}', name: 'post_view')]
    public function post(
		Request $request, 
		Post $post, 
		CommentService $commentService,
		CommentRepository $commentRepository
	): Response
    {
        if(!$this->canUserView($post)) {
			throw $this->createNotFoundException('Post not found');
		}

		if($post->getLang() !== $request->getLocale()) {
			return $this->redirectEntityToCurrentLocale($post, 'post_view');
		}

		$commentForm = $commentService->createForm();
		$commentForm->handleRequest($request);
		if($commentForm->isSubmitted() && $commentForm->isValid()) {
			$commentFormData = $commentForm->getData();
			$commentService->handleForm($commentFormData, $post, $request);
			return $this->redirect($request->getUri());
		}

        return $this->render('blog/post.html.twig', [
            'post'      => $post,
            'comments'  => [
				'entities' => $commentRepository->findBy(['post' => $post, 'status' => Comment::STATUS_APPROVED], ['createdAt' => 'DESC']),
				'form'   => $commentForm->createView(),
			],
			'relatedPosts' => $post->getRelatedPosts(),
			'translatedEntities' => ['entities' => $post->getTranslatedPosts()->toArray(), 'fallback_route' => 'blog'],
            'page'      => 'post'
        ]);
    }

	#[Route(name: 'post_locale_redirect')]
	public function postLocaleRedirect(Post $post): Response
	{
		return $this->redirectEntityToCurrentLocale($post, 'post_view');
	}
}