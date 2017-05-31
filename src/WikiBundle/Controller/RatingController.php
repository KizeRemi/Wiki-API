<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\Revision;
use WikiBundle\Entity\Rating;

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

class RatingController extends Controller implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *  section="Ratings",
     *  description="Add rate to a revision",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Rating added",
     *     400 = "Bad request",
     *	   401 = "Unauthorized"
     *   }
     * )
     * @RequestParam(name="rating", requirements="\d+",description="The note for rate a revision (0 to 5)")
     * @FOSRest\Post("/rating/{revision}")
     * @ParamConverter("revision", class="WikiBundle:Revision")
     * @Security("has_role('ROLE_USER')")
     */    
    public function postAction(ParamFetcherInterface $paramFetcher, Revision $revision)
    {
    	$ratingManager = $this->get('wiki.rating_manager');
    	$user = $this->getUser();
		$note = $paramFetcher->get('rating');
    	if(!$ratingManager->checkNote($note)){
    		$resp = array("message" => "La note doit être comprise entre 0 et 5.");
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);
    	}
    	if(!$ratingManager->checkStatus($revision)){
    		$resp = array("message" => "Cette révision n'est pas en ligne.");
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);
    	}
    	if($ratingManager->hasRateRevision($revision,$user)){
    		$resp = array("message" => "Cette utilisateur a déjà noté cette révision.");
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);    		
    	}
    	$ratingManager->addRating($revision, $note);
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *  section="Ratings",
     *  description="Update a rating revision",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Rating updated",
     *     400 = "Bad request",
     *	   401 = "Unauthorized"
     *   }
     * )
     * @RequestParam(name="rating", requirements="\d+",description="The note for rate a revision (0 to 5)")
     * @FOSRest\Patch("/rating/{rating}")
     * @ParamConverter("rating", class="WikiBundle:Rating")
     * @Security("has_role('ROLE_USER')")
     */    
    public function patchAction(ParamFetcherInterface $paramFetcher, Rating $rating)
    {
    	$ratingManager = $this->get('wiki.rating_manager');
    	$user = $this->getUser();
    	$note = $paramFetcher->get('rating');

    	if(!$ratingManager->checkNote($note)){
    		$resp = array("message" => "La note doit être comprise entre 0 et 5.");
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);
    	}
    	if(!$ratingManager->checkStatus($rating->getRevision())){
    		$resp = array("message" => "Cette révision n'est pas en ligne.");
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);
    	}
    	if($rating->getUser() != $user){
     		$resp = array("message" => "Vous n'êtes pas autorisé à mettre à jour cette note.");
            return new JsonResponse($resp, JsonResponse::HTTP_UNAUTHORIZED);   		
    	}
    	$ratingManager->updateRating($rating, $note);
        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *  section="Ratings",
     *  description="Average rating for a revision",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "Bad request",
     *	   404 = "Not found"
     *   }
     * )
     * @FOSRest\GET("/rating/{revision}/average")
     * @ParamConverter("revision", class="WikiBundle:Revision")
     */    
    public function getAverageAction(Revision $revision)
    {
    	$em = $this->getDoctrine()->getManager();
        $averageRating = $em->getRepository('WikiBundle:Rating')->getAverageRatingByRevision($revision);
        return new JsonResponse($averageRating, JsonResponse::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *  section="Ratings",
     *  description="Rating counter for a revision",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "Bad request",
     *	   404 = "Not found"
     *   }
     * )
     * @FOSRest\GET("/rating/{revision}/count")
     * @ParamConverter("revision", class="WikiBundle:Revision")
     */    
    public function getCountAction(Revision $revision)
    {
    	$em = $this->getDoctrine()->getManager();
        $ratingCount = $em->getRepository('WikiBundle:Rating')->getRatingCountByRevision($revision);
        return new JsonResponse($ratingCount, JsonResponse::HTTP_OK);
    }
}