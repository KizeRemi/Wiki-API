<?php
namespace WikiBundle\Controller;

use WikiBundle\Entity\Revision;
use WikiBundle\Entity\Page;
use WikiBundle\Entity\Category;
use WikiBundle\Entity\ContentImage;
use WikiBundle\Service\FileUploader;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Filesystem\Filesystem;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ContentImageController extends Controller implements ClassResourceInterface
{
    /**
     *
     * @ApiDoc(
     *  section="Images",
     *  description="Add an image in the gallery of a page",
     *  resource = true,
     *  statusCodes = {
     *     201 = "Successful",
     *     404 = "Not found"
     *   }
     * )
     * @ParamConverter("page", class="WikiBundle:Page")
     * @RequestParam(name="image", nullable=false, description="Image")
     * @FOSRest\Post("/page/{page}/image")
     * @FOSRest\FileParam(name="content-image", nullable=true, description="Additional image")
     * @Security("has_role('ROLE_USER')")
     */
    public function postAction(ParamFetcherInterface $paramFetcher, Page $page)
    {
        $em = $this->getDoctrine()->getManager();

        $image = $paramFetcher->get('content-image');

        $fileUploader = $this->get('wiki.file_uploader_content');
        $fileName = $fileUploader->upload($image);

        $contentImage = new ContentImage();
        $contentImage->setFilename($fileName);

        $page->addContentImage($contentImage);

        $em->persist($page);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     *
     * @ApiDoc(
     *  section="Images",
     *  description="Delete an image from the gallery of a page",
     *  resource = true,
     *  statusCodes = {
     *     204 = "No content",
     *     404 = "Not found"
     *   }
     * )
     * @ParamConverter("image", class="WikiBundle:ContentImage")
     * @FOSRest\Delete("/image/{image}")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(ContentImage $image)
    {
        $em = $this->getDoctrine()->getManager();

        // Delete the image file
        $fs = new Filesystem();
        $filename = $image->getFilename();
        if (isset($filename) && $filename != '') {
            $fs->remove(array($this->getParameter('upload_path_content') . '/' . $filename));
        }

        $page = $image->getPage();

        // Removes the image in DB
        $page->removeContentImage($image);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}