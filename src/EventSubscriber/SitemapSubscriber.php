<?php

namespace App\EventSubscriber;

use App\Repository\PostRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSubscriber implements EventSubscriberInterface
{
    /**
     * @var PostRepository
     */
    private $postRepository;
    
	/**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository, ProjectRepository $projectRepository)
    {
        $this->postRepository = $postRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::class => 'populate',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function populate(SitemapPopulateEvent $event): void
    {
        $this->registerUrls(
			$event->getUrlContainer(), 
			$event->getUrlGenerator(),
			$this->postRepository->findBy(['status' => 'publish']),
			'post_view',
			'blog'
		);

        $this->registerUrls(
			$event->getUrlContainer(), 
			$event->getUrlGenerator(),
			$this->projectRepository->findBy(['status' => 'publish']),
			'project_view',
			'projets'
		);
    }

    /**
     * @param UrlContainerInterface $urls
     * @param UrlGeneratorInterface $router
	 * @param App\Entity[] $entities
	 * @param string $routeName
	 * @param string $sectionSitemap
     */
    public function registerUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router, $entities, string $routeName, string $sitemapSection): void
    {
        foreach ($entities as $entity) {
            $urls->addUrl(
                new UrlConcrete(
                    $router->generate(
                        $routeName,
                        ['slug' => $entity->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                $sitemapSection
            );
        }
    }
}