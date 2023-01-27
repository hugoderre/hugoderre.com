<?php

namespace App\Form\Service;

use App\Entity\Comment;
use App\Entity\PostType\AbstractPostType;
use App\Entity\PostType\Post;
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
			$this->logger->info(sprintf('Honeypot caught from %s', $request->getClientIp()), $formData);
			throw new \RuntimeException('Blatant spam, go away!');
		}
		
		$comment = new Comment();
		$comment->setPost($post)
				->setCreatedAt(new \DateTimeImmutable())
				->setClientIp($request->getClientIp())
				->setUserAgent($request->headers->get('User-Agent'))
				->setAuthorName($formData['authorName'])
				->setAuthorEmail($formData['authorEmail'])
				->setAuthorWebsite($formData['authorWebsite'])
				->setContent($formData['content']);

		if(is_numeric($formData['parentId'])) {
			$parentComment = $this->commentRepository->find($formData['parentId']);

			if($parentComment && $parentComment->getPost() === $post) { // Make sure the parent comment is on the same post
				$comment->setParent($parentComment);
			}
		}
		
		$spamScore = $this->spamChecker->getSpamScore($comment);
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