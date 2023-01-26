<?php

namespace App\Security;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpamCheckerService
{
    private $endpoint;

    public function __construct(private HttpClientInterface $client, private RequestStack $requestStack, private string $ASKIMET_KEY)
    {
        $this->endpoint = sprintf('https://%s.rest.akismet.com/1.1/comment-check', $this->ASKIMET_KEY);
    }

    /**
     * @return int Spam score: 0: not spam, 1: maybe spam, 2: blatant spam
     *
     * @throws \RuntimeException if the call did not work
     */
    public function getSpamScore(Comment $comment, array $context = []): int
    {
		$request = $this->requestStack->getCurrentRequest();

		$context = array_merge($context, [
			'user_ip' => $request->getClientIp(),
			'user_agent' => $request->headers->get('User-Agent'),
			'referrer' => $request->headers->get('Referer'),
			'permalink' => $request->getUri(),
		]);

        $response = $this->client->request('POST', $this->endpoint, [
            'body' => array_merge($context, [
                'blog' => 'https://hugoderre.fr',
                'comment_type' => 'comment',
                'comment_author' => $comment->getAuthorName(),
				'comment_author_email' => $comment->getAuthorEmail(),
                'comment_content' => $comment->getContent(),
                'comment_date_gmt' => $comment->getCreatedAt()->format('c'),
                'blog_lang' => 'fr',
                'blog_charset' => 'UTF-8',
                'is_test' => true,
            ]),
        ]);

        $headers = $response->getHeaders();
        if ('discard' === ($headers['x-akismet-pro-tip'][0] ?? '')) {
            return 2;
        }

        $content = $response->getContent();
        if (isset($headers['x-akismet-debug-help'][0])) {
            throw new \RuntimeException(sprintf('Unable to check for spam: %s (%s).', $content, $headers['x-akismet-debug-help'][0]));
        }

        return 'true' === $content ? 1 : 0;
    }
}