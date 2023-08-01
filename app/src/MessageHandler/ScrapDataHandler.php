<?php
namespace App\MessageHandler;

use App\Entity\Article;
use App\Message\ScrapDataBroker;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ScrapDataHandler implements MessageHandlerInterface
{
    /**
     * @var ArticleRepository
     */
    private ArticleRepository $articleRepository;
    /**
     * @var ManagerRegistry
     */
    private ManagerRegistry $doctrine;

    /**
     * @param ArticleRepository $articleRepository
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ArticleRepository $articleRepository, ManagerRegistry $doctrine)
    {
        $this->articleRepository = $articleRepository;
        $this->doctrine = $doctrine;
    }

    /**
     * @param ScrapDataBroker $message
     * @return void
     */
    public function __invoke(ScrapDataBroker $message)
    {
        $entityManager = $this->doctrine->getManager();
        foreach ($message->getContent() as $data) {
            $article = $this->articleRepository->findOneBy(['title' => $data['title']]);
            if (empty($article)) {
                $article = new Article();
                $article->setTitle($data['title']);
            }
            $article->setDescription($data['description'] ?? '');
            $article->setImage($data['image'] ?? '');
            $article->setDate($data['dateTime'] ?? '');

            $entityManager->persist($article);
        }
        $entityManager->flush();
        $entityManager->clear();
    }
}