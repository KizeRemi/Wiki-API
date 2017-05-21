<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\Revision;
use WikiBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as FOSRest;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class RevisionController extends Controller implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *  section="Revisions",
     *  description="Get revisions for a page",
     *  requirements={
     *      {
     *          "name"="page",
     *          "dataType"="Page",
     *          "description"="The page for which you want the revisions"
     *      }
     *  },
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     404 = "Page not found"
     *   }
     * )
     * @FOSRest\Get("/page/{page}/revisions")
     */
    public function cgetAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('WikiBundle:Revision')->findByPage($page);

        return $pages;
    }

    /**
     * @ApiDoc(
     *  section="Revisions",
     *  description="Get a revision",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Created",
     *     400 = "Error"
     *   }
     * )
     * @ParamConverter("revision", class="WikiBundle:Revision")
     * @FOSRest\Get("/page/{page}/revision/{revision}")
     */
    public function getAction(Page $page, Revision $revision)
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('WikiBundle:Revision')->findByPage($page);
        return $pages;
    }

    /**
     * @ApiDoc(
     *  section="Revisions",
     *  description="create a revision for a page",
     *  requirements={
     *      {
     *          "name"="page",
     *          "dataType"="Page",
     *          "description"="Create a revision for a page"
     *      }
     *  },
     *  resource = true,
     *  statusCodes = {
     *     201 = "Created",
     *     400 = "Error"
     *   }
     * )
     * @RequestParam(name="title", nullable=false, description="Revision's title")
     * @RequestParam(name="content", nullable=false, description="Revision's content")
     * @FOSRest\Post("/page/{page}/revision/{revision}")
     */
    public function postAction(ParamFetcherInterface $paramFetcher, Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $revision = new Revision();

        $revision->setPage($page);
        $revision->setStatus('Pending');

        $revision->setTitle($paramFetcher->get('title'));
        $revision->setContent($paramFetcher->get('content'));

        $em->persist($revision);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     *
     * @ApiDoc(
     *  section="Revisions",
     *  description="Delete a revision for a page",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     404 = "Not found"
     *   }
     * )
     * @FOSRest\Delete("/page/{page}/revision/{revision}")
     */
    public function deleteAction(Page $page, Revision $revision)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($revision);
        $em->flush($revision);

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }
}