<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\Revision;
use WikiBundle\Entity\Page;
use WikiBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class PageController extends Controller implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *  section="Pages",
     *  description="Get all pages",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     404 = "Not found"
     *   }
     * )
     */
    public function cgetAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('WikiBundle:Page')->findAll();

        return $pages;
    }

    /**
     * @param Page $page
     * @ApiDoc(
     *  section="Pages",
     *  description="Get a page",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Successful",
     *     404 = "Not found"
     *   }
     * )
     * @ParamConverter("page", class="WikiBundle:Page")
     * @FOSRest\Get("/page/{page}")
     */
    public function getAction(Page $page)
    {
        return $page;
    }

    /**
     * @ApiDoc(
     *   section="Pages",
     *   description="Get all pages by a category with filters",
     *   requirements={
     *      {
     *          "name"="category",
     *          "dataType"="Category",
     *          "description"=""
     *      }
     *   },
     *   resource = true,
     *   statusCodes = {
     *     200 = "Successful",
     *     404 = "Not found"
     *   }
     * )
     * @QueryParam(name="offset", requirements="\d+", default="", description="Index of beginning of pagination")
     * @QueryParam(name="limit", requirements="\d+", default="", description="Number of pages to display")
     * @FOSRest\Get("/pages/category/{category}")
     */
    public function cgetCategoryAction(ParamFetcherInterface $paramFetcher, Category $category)
    {

        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('WikiBundle:Page')->getPagesByCategoryWithOffsetAndLimit($category, $offset, $limit);
        return $pages;
    }

    /**
     * @ApiDoc(
     *  section="Pages",
     *  description="Create a new page",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Page created",
     *     400 = "Bad request"
     *   }
     * )
     * @RequestParam(name="category", description="Category's page")
     * @RequestParam(name="title", nullable=false, description="Revision's title")
     * @RequestParam(name="content", nullable=false, description="Revision's content")
     * @FOSRest\Post("/page")
     */    
    public function postAction(ParamFetcherInterface $paramFetcher)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $page = new Page();
        $categoryId = $paramFetcher->get('category');
        $category = $em->getRepository('WikiBundle:Category')->find($categoryId);
        if(!$category){
            $resp = "Cette catégorie n'existe pas.";
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);
        }
        $status = $this->getDoctrine()->getRepository('WikiBundle:Status')->find(2);
        $revision = new Revision();
        $revision->setUser($this->getUser());
        $revision->setStatus($status);
        $revision->setTitle($paramFetcher->get('title'));
        $revision->setContent($paramFetcher->get('content'));
        $page->addRevision($revision);
        $page->setCategory($category);
        $em->persist($page);
        $em->flush($page);

        return $page;
    }

    /**
     *
     * @ApiDoc(
     *  section="Revisions",
     *  description="Delete a page and all his revisions",
     *  resource = true,
     *  statusCodes = {
     *     204 = "No content",
     *     404 = "Not found"
     *   }
     * )
     * @ParamConverter("page", class="WikiBundle:Page")
     * @FOSRest\Delete("/page/{page}")
     */
    public function deleteAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush($page);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}