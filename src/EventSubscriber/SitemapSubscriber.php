<?php

namespace App\EventSubscriber;

use App\Helpers\LocaleHelpers;
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
	 * @var array
	 */
	private $locales;

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
    public function __construct(
		PostRepository $postRepository, 
		ProjectRepository $projectRepository, 
		LocaleHelpers $localesHelper
	)
    {
        $this->postRepository = $postRepository;
        $this->projectRepository = $projectRepository;
		$this->locales = $localesHelper->getLocalesList();
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
		$staticRoutes = [
			'homepage',
			'blog',
			'projects',
		];

		foreach ($this->locales as $locale) {
			foreach ($staticRoutes as $staticRoute) {
				$event->getUrlContainer()->addUrl(
					new UrlConcrete(
						$event->getUrlGenerator()->generate($staticRoute, ['_locale' => $locale], UrlGeneratorInterface::ABSOLUTE_URL)
					),
					'static'
				);
			}	

			$this->registerEntitiesUrls(
				$event->getUrlContainer(), 
				$event->getUrlGenerator(),
				$this->postRepository->findBy(['status' => 'publish', 'lang' => $locale]),
				'post_view',
				'blog'
			);
	
			$this->registerEntitiesUrls(
				$event->getUrlContainer(), 
				$event->getUrlGenerator(),
				$this->projectRepository->findBy(['status' => 'publish', 'lang' => $locale]),
				'project_view',
				'projects'
			);
		}
    }

    /**
     * @param UrlContainerInterface $urls
     * @param UrlGeneratorInterface $router
	 * @param App\Entity[] $entities
	 * @param string $routeName
	 * @param string $sectionSitemap
     */
    public function registerEntitiesUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router, $entities, string $routeName, string $sitemapSection): void
    {
        foreach ($entities as $entity) {
			$urls->addUrl(
				new UrlConcrete(
					$router->generate(
						$routeName,
						['slug' => $entity->getSlug(), '_locale' => $entity->getLang()],
						UrlGeneratorInterface::ABSOLUTE_URL
					)
				),
				$sitemapSection
			);
        }
    }
}