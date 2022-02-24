<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class ArticleController extends AbstractFOSRestController
{
    /**
     * @Get(
     *     path = "/articles/{id}",
     *     name = "app_article_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View
     */
    public function showAction()
    {
        $article = new Article();
        $article->setTitle("Nouveau titre");
        $article->setContent("Contenu du nouveau article.");
        return $article;
    }

    /**
     * @Post(
     *     path="/articles",
     *     name="app_article_show"
     * )
     * @View(StatusCode=201)
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function createAction(Article $article, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $em->persist($article);
        $em->flush();

        return $this->view(
            $article,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('app_article_show',['id' => $article->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
            ]
        );
    }
}