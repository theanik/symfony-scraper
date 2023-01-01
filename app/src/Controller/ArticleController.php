<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Scraper\Scraper;
use App\Scraper\Source\TheWallStreetJournal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private Scraper $scraper;

    public function __construct(Scraper $scraper)
    {
        $this->scraper = $scraper;
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        $currentPage = $request->get('page') ?? 1;
        $articlePaginator = $articleRepository->getPaginatedArticle($currentPage);
        $totalPage = ceil($articlePaginator->count() / $articleRepository->getPerPage());

        $articles = $articlePaginator->getIterator();

        return $this->render('articles/index.html.twig', [
            'currentPage' => $currentPage,
            'totalPage' => $totalPage,
            'articles' => $articles,
            'nbPages' => $totalPage,
            'user' => $this->getUser()
        ]);
    }
}
