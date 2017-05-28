<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SecurityController extends Controller implements ClassResourceInterface
{
   /**
     * @ApiDoc(
     *  section="Security",
     *  description="login and return a token user",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Return a token",
     *     400 = "Bad credentials"
     *   }
     * )
     * @RequestParam(name="_username", description="Username")
     * @RequestParam(name="_password", nullable=false, description="Password")
     * @FOSRest\Post("/login_check")
     */  
    public function loginAction()
    {
    }

    /**
     * @ApiDoc(
     *  section="Security",
     *  description="Set/unset a role admin to user",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     403 = "Access denied",
     *     404 = "User not found"
     *   }
     * )
     * @FOSRest\Patch("/user/{user}/admin")
     * @Security("has_role('ROLE_ADMIN')")
     */  
    public function toggleRoleAdminAction(User $user)
    {
        $userManager = $this->get("fos_user.user_manager");
        if($user->hasRole('ROLE_ADMIN')){
            $user->removeRole('ROLE_ADMIN');
        } else {
            $user->addRole('ROLE_ADMIN');
        }
        $userManager->updateUser($user);
        return $user;
    }

    /**
     * @ApiDoc(
     *  section="Security",
     *  description="Enabled a user",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "User already enabled",
     *     403 = "Access denied",
     *     404 = "User not found"
     *   }
     * )
     * @FOSRest\Patch("/user/{user}/enabled")
     * @Security("has_role('ROLE_ADMIN')")
     */  
    public function enabledUserAction(User $user)
    {
        $userManager = $this->get("fos_user.user_manager");
        if($user->isEnabled()){
            $resp = array("message" => "Cette compte utilisateur est déjà activé.");
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);
        } 
        $user->setEnabled(1);
        $userManager->updateUser($user);
        return $user;
    }

    /**
     * @ApiDoc(
     *  section="Security",
     *  description="Disabled a user",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "User already disabled",
     *     403 = "Access denied",
     *     404 = "User not found"
     *   }
     * )
     * @FOSRest\Patch("/user/{user}/disabled")
     * @Security("has_role('ROLE_ADMIN')")
     */  
    public function disabledUserAction(User $user)
    {
        $userManager = $this->get("fos_user.user_manager");
        if(!$user->isEnabled()){
            $resp = array("message" => "Ce compte utilisateur est déjà désactivé.");
            return new JsonResponse($resp, JsonResponse::HTTP_BAD_REQUEST);
        } 
        $user->setEnabled(0);
        $userManager->updateUser($user);
        return $user;
    }
}