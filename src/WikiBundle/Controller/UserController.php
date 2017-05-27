<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as FOSRest;

use UserBundle\Event\RegistrationEvent;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UserController extends Controller implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *  section="Users",
     *  description="Get all users",
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
        $users = $em->getRepository('WikiBundle:User')->findAll();

        return $users;
    }

    /**
     * @ApiDoc(
     *  section="Users",
     *  description="Get a user",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Successful",
     *     404 = "Not found"
     *   }
     * )
     * @ParamConverter("user", class="WikiBundle:User")
     * @FOSRest\Get("/user/{user}")
     */
    public function getAction(User $user)
    {
        return $user;
    }

    /**
     * @ApiDoc(
     *  section="Users",
     *  description="Create new user",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "Password and confirmation doesn't match"
     *   }
     * )
     *
     * @RequestParam(name="email", nullable=false, description="Account's email")
     * @RequestParam(name="password", nullable=false, description="Account's password")
     * @RequestParam(name="password_confirmation", nullable=false, description="Password confirmation")
     * @RequestParam(name="username", nullable=false, description="Account's nickname")
     * @FOSRest\Post("/user")
     */
    public function postAction(ParamFetcherInterface $paramFetcher)
    {
        if ($paramFetcher->get('password') !== $paramFetcher->get('password_confirmation')) {
            $resp = array("message" => "Password and confirmation password doesn't match");
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);
        }

        $userManager = $this->get("fos_user.user_manager");
        $user = $userManager->createUser();
        $user->setEmail($paramFetcher->get('email'));
        $user->setUsername(ucfirst($paramFetcher->get('username')));
        $user->setPlainPassword($paramFetcher->get('password'));

        $validator = $this->get("validator");
        $errors = $validator->validate($user);
        if(count($errors) > 0){
            return new JsonResponse($errors[0]->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        } 

        $userManager->updateUser($user);
        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @ApiDoc(
     *  section="Users",
     *  description="Update the profil of user connected",
     *  resource = true,
     *  type="string",
     *  statusCodes = {
     *     200 = "Successful",
     *     404 = "Not found"
     *   }
     * )
     * @FOSRest\RequestParam(name="birth_date", requirements=@WikiBundle\Validator\Constraints\Date, nullable=false, description="User's birthday")
     * @FOSRest\RequestParam(name="name", nullable=true, description="User's name")
     * @FOSRest\RequestParam(name="lastname", nullable=true, description="User's lastname")
     * @FOSRest\Patch("/profile")
     * @Security("has_role('ROLE_USER')")
     */
    public function patchProfileAction(ParamFetcherInterface $paramFetcher)
    {
        $user = $this->getUser();

        $user->setName(ucfirst($paramFetcher->get('name')));
        $user->setLastname(ucfirst($paramFetcher->get('lastname')));
        if($paramFetcher->get('birth_date') != null){
            $birthDate = new \DateTime($paramFetcher->get('birth_date'));
            $user->setBirthDate($birthDate);         
        }
        $validator = $this->get("validator");
        $errors = $validator->validate($user);
        if(count($errors) > 0){
            return new JsonResponse($errors[0]->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }
        $userManager = $this->get("fos_user.user_manager");
        $userManager->updateUser($user);
        return $user;
    }

}