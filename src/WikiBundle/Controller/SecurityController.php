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

class SecurityController extends Controller implements ClassResourceInterface
{
   /**
     * @ApiDoc(
     *  section="Users",
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
}