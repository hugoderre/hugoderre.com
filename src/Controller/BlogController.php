<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\Type\Post\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Security\SpamCheckerService;
use App\Trait\PostTypeTrait;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
	use PostTypeTrait;

    #[Route('/blog', name: 'blog', options: ['sitemap' => true])]
    // #[ParamConverter('post')]
    public function all(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy( ['status' => 'publish'], ['createdAt' => 'DESC'] );

        return $this->render('blog/blog.html.twig', [
            'posts' => $posts,
            'page'  => 'blog',
        ]);
        
    }

    #[Route('/blog/{slug}', name: 'post_view')]
    public function post(
		Request $request, 
		Post $post, 
		CommentRepository $commentRepository, 
		LoggerInterface $logger,
		SpamCheckerService $spamChecker
	): Response
    {
        if(!$this->canUserView($post)) {
			throw $this->createNotFoundException('Post not found');
		}
		
		$commentForm = $this->createForm(CommentType::class);
		$commentForm->handleRequest($request);
		if($commentForm->isSubmitted() && $commentForm->isValid()) {
			$commentFormData = $commentForm->getData();

			// Honneypot check
			if(!empty($commentFormData['country'])) {
				$logger->info(sprintf('Honneypot caught from %s', $request->getClientIp()), $commentForm->getData());
				throw new \RuntimeException('Blatant spam, go away!');
			}

			$comment = new Comment();
			$comment->setPost($post);
			$comment->setCreatedAt(new \DateTimeImmutable());
			$comment->setClientIp($request->getClientIp());
			$comment->setUserAgent($request->headers->get('User-Agent'));
			$comment->setAuthorName($commentFormData['authorName']);
			$comment->setAuthorEmail($commentFormData['authorEmail']);
			$comment->setAuthorWebsite($commentFormData['authorWebsite']);
			$comment->setContent($commentFormData['content']);
			
			$spamScore = $spamChecker->getSpamScore($comment, [
				'user_ip' => $request->getClientIp(),
				'user_agent' => $request->headers->get('User-Agent'),
				'referrer' => $request->headers->get('Referer'),
				'permalink' => $request->getUri(),
			]);

			$comment->setSpamScore($spamScore);
			$comment->setStatus($spamScore > 0 ? Comment::STATUS_PENDING : Comment::STATUS_APPROVED);

			$commentRepository->add($comment, true);

			return $this->redirect($request->getUri());
		}

        return $this->render('blog/post.html.twig', [
            'post'      => $post,
            'comments'  => [
				'posted' => $commentRepository->findBy(['post' => $post, 'status' => Comment::STATUS_APPROVED]),
				'form'   => $commentForm->createView(),
			],
			'relatedPosts' => $post->getRelatedPosts(),
            'page'      => 'post'
        ]);
    }
}