<?php

namespace WikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * ContentImage
 *
 * @ORM\Table(name="content_image")
 * @ORM\Entity(repositoryClass="WikiBundle\Repository\ContentImageRepository")
 */
class ContentImage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, unique=true)
     */
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity="WikiBundle\Entity\Page", inversedBy="contentImages")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    protected $page;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return ContentImage
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set page
     *
     * @param \WikiBundle\Entity\Page $page
     *
     * @return ContentImage
     */
    public function setPage(\WikiBundle\Entity\Page $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \WikiBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }
}
