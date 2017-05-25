<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\Revision;
use WikiBundle\Entity\Page;
use WikiBundle\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class RevisionController extends Controller implements ClassResourceInterface
{ 
    /**
     * @ApiDoc(
     *  section="Revisions",
     *  description="Get all revisions for a page",
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
        $revisions = $em->getRepository('WikiBundle:Revision')->findByPage($page);

        return $revisions;
    }

    /**
     * @ApiDoc(
     *  section="Revisions",
     *  description="Get a revision",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Successful",
     *     404 = "Page not found"
     *   }
     * )

     * @Route(requirements={"revision"="\d+"})
     * @ParamConverter("revision", class="WikiBundle:Revision")
     * @FOSRest\Get("/page/{page}/revision/{revision}", requirements={"revision" = "\d+"},)
     */
    public function getAction(Page $page, Revision $revision)
    {
        return $revision;
    }

    /**
     * @ApiDoc(
     *  section="Revisions",
     *  description="Get latest online revision for a page",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Successful",
     *     400 = "Not found"
     *   }
     * )
     * @ParamConverter("page", class="WikiBundle:Page")
     * @FOSRest\Get("/page/{page}/revision/latest")
     */
    public function getLatestAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $revision = $em->getRepository('WikiBundle:Revision')->getLatestOnlineRevisionByPage($page);
        return $revision;
    }

    /**
     * @ApiDoc(
     *  section="Revisions",
     *  description="Get all revisions by page and status",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Successful",
     *     400 = "Not found"
     *   }
     * )
     * @ParamConverter("page", class="WikiBundle:Page")
     * @ParamConverter("status", class="WikiBundle:Status")
     * @FOSRest\Get("/page/{page}/status/{status}/revisions")
     */
    public function cgetStatusAction(Page $page, Status $status)
    {
        $em = $this->getDoctrine()->getManager();
        $revisions = $em->getRepository('WikiBundle:Revision')->findBy([ 'page' => $page, 'status' => $status]);
        return $revisions;
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
     * @FOSRest\Post("/page/{page}/revision")
     */
    public function postAction(ParamFetcherInterface $paramFetcher, Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $status = $this->getDoctrine()->getRepository('WikiBundle:Status')->find(2);
        $revision = new Revision();

        $revision->setPage($page);
        $revision->setStatus($status);
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
     *  description="Change a status for a revision",
     *  resource = true,
     *  statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Not found"
     *   }
     * )
     * @FOSRest\Patch("/page/{page}/revision/{revision}/status/{status}")
     */
    public function patchStatusAction(Page $page, Revision $revision, Status $status)
    {
        $em = $this->getDoctrine()->getManager();
        if($status->getId() == 2){
            $resp = array("message" => "Cette revision a déjà été traitée. ");
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);           
        }
        if($revision->getStatus() == $status){
            $resp = array("message" => "Cette revision possède déjà le status: ".$revision->getStatus()->getName());
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);
        }
        $revision->setStatus($status);
        $em->persist($revision);
        $em->flush($revision);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
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