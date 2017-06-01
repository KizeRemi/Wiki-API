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

class StatisticController extends Controller implements ClassResourceInterface
{
   /**
     * @ApiDoc(
     *  section="Statistics",
     *  description="Return top 10 best contributors",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Return 10 users and number of revisions for each.",
     *     404 = "Not found"
     *   }
     * )
     * @FOSRest\Get("/statistic/contributors/top")
     */  
    public function getTopContributorsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('WikiBundle:Revision')->getTopContributors();
        return $result;
    }

   /**
     * @ApiDoc(
     *  section="Statistics",
     *  description="Return top 10 pages",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Return top 10 pages by counter view.",
     *     404 = "Not found"
     *   }
     * )
     * @FOSRest\Get("/statistic/pages/top")
     */  
    public function getTopPagesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('WikiBundle:Page')->getTopPages();
        return $result;
    }
}