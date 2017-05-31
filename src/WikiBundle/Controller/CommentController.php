<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\Comment;
use WikiBundle\Entity\Page;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class CommentController extends Controller implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *  section="Comments",
     *  description="Add comment to a page",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Comment added",
     *     400 = "Bad request",
     *	   401 = "Unauthorized"
     *   }
     * )
     * @RequestParam(name="content", nullable=false, description="The content of the commentary")
     * @FOSRest\Post("/page/{page}/comment")
     * @ParamConverter("page", class="WikiBundle:Page")
     * @Security("has_role('ROLE_USER')")
     */    
    public function postAction(ParamFetcherInterface $paramFetcher, Page $page)
    {
        $em = $this->getDoctrine()->getManager();
    	$comment = new Comment();

        $comment->setContent($paramFetcher->get('content'));
        $comment->setUser($this->getUser());
        $comment->setPage($page);

        $em->persist($comment);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *  section="Comments",
     *  description="Get all comments for a page with offset and limit",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "Bad request",
     *     404 = "Page not found"
     *
     *   }
     * )
     * @QueryParam(name="offset", requirements="\d+", default="", description="Index of beginning of pagination")
     * @QueryParam(name="limit", requirements="\d+", default="", description="Number of pages to display")
     * @FOSRest\Get("/page/{page}/comments")
     * @ParamConverter("page", class="WikiBundle:Page")
     */    
    public function getAction(ParamFetcherInterface $paramFetcher, Page $page)
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('WikiBundle:Comment')->searchCommentsWithOffsetAndLimit($page, $offset, $limit);
        
        return $comments;
    }

    /**
     * @ApiDoc(
     *  section="Comments",
     *  description="Delete a commentary",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Delete successful",
     *     400 = "Bad request",
     *     404 = "Page not found"
     *
     *   }
     * )
     * @FOSRest\Delete("/comment/{comment}")
     * @ParamConverter("comment", class="WikiBundle:Comment")
     */    
    public function deleteAction(ParamFetcherInterface $paramFetcher, Comment $comment)
    {
        if($comment->getUser() != $this->getUser()){
            $resp = array("message" => "Vous n'êtes pas autorisé à supprimer ce commentaire.");
            return new JsonResponse($resp, JsonResponse::HTTP_UNAUTHORIZED);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }
}