<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     *     400 = "Bad request",
     *     403 = "Access denied"
     *   }
     * )
     * @FOSRest\Patch("/user/{user}/admin")
     * @Security("has_role('ROLE_ADMIN')")
     */  
    public function toggleRoleAdminAction(User $user)
    {
        $user = $this->getUser();
        $userManager = $this->get("fos_user.user_manager");
        if($user->hasRole('ROLE_ADMIN')){
            $user->removeRole('ROLE_ADMIN');
        } else {
            $user->addRole('ROLE_ADMIN');
        }
        $userManager->updateUser($user);
        return $user;
    }
}