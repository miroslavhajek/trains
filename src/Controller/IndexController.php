<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(PageRepository $pageRepository): Response
    {
        $pages = $pageRepository->findAll();

        return $this->render('index/index.html.twig', [
            'pages' => $pages,
        ]);
    }
}
