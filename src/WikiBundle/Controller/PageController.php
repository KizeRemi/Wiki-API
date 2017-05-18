<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class PageController extends Controller implements ClassResourceInterface
{
    /**
     * @View()
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
     * @param User $user
     * @ApiDoc(
     *  section="Users",
     *  description="Get a page",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Successful",
     *     404 = "Not found"
     *   }
     * )
     * @View()
     * @ParamConverter("page", class="WikiBundle:Page")
     */
    public function getAction(Page $page)
    {
        return $page;
    }
}