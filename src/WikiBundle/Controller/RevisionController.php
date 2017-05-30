<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\Revision;
use WikiBundle\Entity\Page;
use WikiBundle\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
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
     * @FOSRest\Get("/revision/{revision}", requirements={"revision" = "\d+"},)
     */
    public function getAction(Revision $revision)
    {
        $this->get('wiki.counter.counter_view')->addView($revision->getPage());
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
     *   section="Revisions",
     *   description="Search revisions",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Successful",
     *     404 = "Not found"
     *   }
     * )
     * @QueryParam(name="offset", requirements="\d+", default="", description="Index of beginning of pagination")
     * @QueryParam(name="limit", requirements="\d+", default="", description="Number of pages to display")
     * @QueryParam(name="search", default="", description="words")
     * @FOSRest\GET("/search")
     */
    public function cgetCategoryAction(ParamFetcherInterface $paramFetcher)
    {

        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');
        $search = $paramFetcher->get('search');
        $em = $this->getDoctrine()->getManager();
        $revisions = $em->getRepository('WikiBundle:Revision')->searchRevisionsWithOffsetAndLimit($search, $offset, $limit);
        return $revisions;
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
     * @FOSRest\Get("/page/{page}/revisions/status/{status}")
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
     *  description="Create a revision for a page",
     *  requirements={
     *      {
     *          "name"="page",
     *          "dataType"="Page",
     *          "description"="The page for which you want te create a revision"
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
     * @Security("has_role('ROLE_USER')")
     */
    public function postAction(ParamFetcherInterface $paramFetcher, Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $status = $this->getDoctrine()->getRepository('WikiBundle:Status')->find(2);
        $revision = new Revision();
        $hasPendingRevision = $em->getRepository('WikiBundle:Revision')->hasAlreadyPendingRevisionByPage($page, $user);
        if($hasPendingRevision){
            $resp = array("message" => "Vous avez déjà une révision en attente de validation pour cette page.");
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);            
        }
        $revision->setPage($page);
        $revision->setUser($user);
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
     * @FOSRest\Patch("/revision/{revision}/status/{status}")
     */
    public function patchStatusAction(Revision $revision, Status $status)
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
     * @FOSRest\Delete("/revision/{revision}")
     */
    public function deleteAction(Revision $revision)
    {
        $em = $this->getDoctrine()->getManager();
        $countRevisions = $em->getRepository('WikiBundle:Revision')->countRevisionsByPage($revision->getPage());
        if($countRevisions == 1){
            $resp = array("message" => "Suppression impossible. Une page doit comporter au moins 1 révision.");
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);            
        }
        $em->remove($revision);
        $em->flush($revision);

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }
}