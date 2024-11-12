<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function sprintf;

class PageController extends AbstractController
{
    #[Route('/page/{slug}', name: 'app_page')]
    public function index(string $slug, PageRepository $pageRepository): Response
    {
        $page = $pageRepository->findPublishedPage($slug)
            ?? throw $this->createNotFoundException(sprintf('Page "%s" not found', $slug));

        return $this->render('page/index.html.twig', [
            'page' => $page,
        ]);
    }
}
