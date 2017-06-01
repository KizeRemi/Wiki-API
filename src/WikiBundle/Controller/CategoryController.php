<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class CategoryController extends Controller implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *  section="Categories",
     *  description="Get all categories",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     404 = "Page not found",
     *     403 = "Denied access"
     *   }
     * )
     * @Security("has_role('ROLE_USER')")
     */
    public function cgetAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('WikiBundle:Category')->findAll();

        return $categories;
    }

    /**
     * @ApiDoc(
     *  section="Categories",
     *  description="Post a new category",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Created",
     *     404 = "Category not found",
     *     403 = "Denied Access"
     *   }
     * )
     * @RequestParam(name="name", nullable=false, description="Category's name")
     * @FOSRest\Post("/category")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function postAction(ParamFetcherInterface $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        $category = new Category();
        $category->setName($paramFetcher->get('name'));
        $em->persist($category);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @ApiDoc(
     *  section="Categories",
     *  description="Update a category",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     404 = "Category not found"
     *   }
     * )
     * @RequestParam(name="name", nullable=false, description="Category's name")
     * @FOSRest\Patch("/category/{category}")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function patchAction(ParamFetcherInterface $paramFetcher, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $category->setName($paramFetcher->get('name'));
        $em->persist($category);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *  section="Categories",
     *  description="Delete a category",
     *  resource = true,
     *  statusCodes = {
     *     200 = "Successful",
     *     404 = "Category not found"
     *   }
     * )
     * @FOSRest\Delete("/category/{category}")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }
}