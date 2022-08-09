<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Event\PostViewEvent;
use App\Form\Type\Post\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Security\SpamChecker;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BlogController extends AbstractController
{

    #[Route('/blog', name: 'blog')]
    // #[ParamConverter('post')]
    public function all(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy( ['status' => 'publish'] );
        
        return $this->render('blog/blog.html.twig', [
            'posts' => $posts,
            'page'  => 'blog',
        ]);
        
    }

    #[Route('/blog/{slug}', name: 'post_view')]
    public function post(
		Request $request, 
		$slug, Post $post, 
		CommentRepository $commentRepository, 
		EventDispatcherInterface $dispatcher,
		string $akismetKey, 
		HttpClientInterface $httpClient,
		LoggerInterface $logger,
	): Response
    {
        if($post->getStatus() !== 'publish') {
            throw $this->createNotFoundException('Post not found');
        }

        $postViewEvent = new PostViewEvent($post);
        $dispatcher->dispatch($postViewEvent, 'post.view');
		
		$commentForm = $this->createForm(CommentType::class);
		$commentForm->handleRequest($request);
		if($commentForm->isSubmitted() && $commentForm->isValid()) {
			$commentFormData = $commentForm->getData();

			// Honneypot check
			if($commentFormData['country']) {
				$logger->info(sprintf('Honneypot caught from %s', $request->getClientIp()), $commentForm->getData());
				throw new \RuntimeException('Blatant spam, go away!');
			}

			$comment = new Comment();
			$comment->setPost($post);
			$comment->setCreatedAt(new \DateTimeImmutable());
			$comment->setClientIp($request->getClientIp());
			$comment->setUserAgent($request->headers->get('User-Agent'));
			$comment->setAuthorName($commentFormData['username']);
			$comment->setAuthorEmail($commentFormData['email']);
			$comment->setAuthorWebsite($commentFormData['website']);
			$comment->setContent($commentFormData['content']);
			
			$spamChecker = new SpamChecker($httpClient, $akismetKey);
			$spamScore = $spamChecker->getSpamScore($comment, [
				'user_ip' => $request->getClientIp(),
				'user_agent' => $request->headers->get('User-Agent'),
				'referrer' => $request->headers->get('Referer'),
				'permalink' => $request->getUri(),
			]);

			$comment->setSpamScore($spamScore);
			$comment->setStatus($spamScore > 0 ? Comment::STATUS_PENDING : Comment::STATUS_APPROVED);

			$commentRepository->add($comment, true);
			$commentForm = $this->createForm(CommentType::class);
		}

        return $this->render('blog/post.html.twig', [
            'post'      => $post,
            'comments'  => [
				'posted' => $commentRepository->findBy(['post' => $post, 'status' => Comment::STATUS_APPROVED]),
				'form'   => $commentForm->createView(),
			],
            'page'      => 'post',
        ]);
    }
}