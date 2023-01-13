<?php

namespace App\Form\Service;

use App\Entity\Comment;
use App\Entity\PostType\AbstractPostType;
use App\Form\Type\Post\CommentType;
use App\Repository\CommentRepository;
use App\Repository\UserRestrictionRepository;
use App\Security\SpamCheckerService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentService
{
	public function __construct(
		private FormFactoryInterface $formFactory,
		private ValidatorInterface $validator,
		private CommentRepository $commentRepository, 
		private LoggerInterface $logger,
		private SpamCheckerService $spamChecker,
		private UserRestrictionRepository $userRestrictionRepository
	) {}

	public function createForm()
	{
		return $this->formFactory->create(CommentType::class);;
	}

	public function handleForm($formData, AbstractPostType $post, Request $request)
	{
		if ($this->userRestrictionRepository->isIpRestricted($request->getClientIp())) {
			return;
		}

		// Honneypot check
		if(!empty($formData['country'])) {
			$this->logger->info(sprintf('Honneypot caught from %s', $request->getClientIp()), $formData);
			throw new \RuntimeException('Blatant spam, go away!');
		}

		$comment = new Comment();
		$comment->setPost($post);
		$comment->setCreatedAt(new \DateTimeImmutable());
		$comment->setClientIp($request->getClientIp());
		$comment->setUserAgent($request->headers->get('User-Agent'));
		$comment->setAuthorName($formData['authorName']);
		$comment->setAuthorEmail($formData['authorEmail']);
		$comment->setAuthorWebsite($formData['authorWebsite']);
		$comment->setContent($formData['content']);
		
		$spamScore = $this->spamChecker->getSpamScore($comment, [
			'user_ip' => $request->getClientIp(),
			'user_agent' => $request->headers->get('User-Agent'),
			'referrer' => $request->headers->get('Referer'),
			'permalink' => $request->getUri(),
		]);

		$comment->setSpamScore($spamScore);

		if($spamScore > 0) {
			$this->logger->info(sprintf('Spam caught from %s', $request->getClientIp()), [
				'score' => $spamScore,
				'comment' => $comment,
			]);

			$comment->setStatus(Comment::STATUS_PENDING);
		} else {
			$comment->setStatus(Comment::STATUS_APPROVED);
		}

		$this->commentRepository->add($comment, true);
	}
}