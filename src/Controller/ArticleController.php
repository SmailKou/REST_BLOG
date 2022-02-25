<?php

namespace App\Controller;

use App\Entity\Article;
use App\Representation\Articles;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function showAction(Article $article): \FOS\RestBundle\View\View
    {
        return $this->view($article, Response::HTTP_ACCEPTED);
    }

    /**
     * @Post(
     *     path="/articles",
     *     name="app_article_create"
     * )
     * @View(StatusCode=201)
     * @ParamConverter("article",
     *     converter="fos_rest.request_body",
     *     options={
     *         "validator"={ "groups"="creation" }
     *     }
     *     )
     */
    public function createAction(Article $article, ManagerRegistry $doctrine, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $em = $doctrine->getManager();
        $em->persist($article);
        $em->flush();

        return $this->view(
            $article,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('app_article_show',
                    [
                        'id' => $article->getId()
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL)
            ]
        );
    }

//    /**
//     * @Rest\Get("/articles", name="app_article_list")
//     */
//    public function listAction(ManagerRegistry $doctrine)
//    {
//        $em = $doctrine->getManager();
//        $articles = $em->getRepository('App\Entity\Article')->findAll();
//
//        dd($articles);
//    }

    /**
     * @Get("/articles", name="app_article_list")
     * @QueryParam(
     *     name="keyword",
     *     requirements="[a-zA-Z0-9]",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
     * @QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Max number of movies per page."
     * )
     * @QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="1",
     *     description="The pagination offset"
     * )
     * @View()
     */
    public function listAction(ParamFetcherInterface $paramFetcher, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $pager = $em->getRepository('App\Entity\Article')->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Articles($pager);
    }
}