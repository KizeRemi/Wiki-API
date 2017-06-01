<?php

namespace WikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation\Exclude;
/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="WikiBundle\Repository\PageRepository")
 */
class Page
{
    use TimestampableEntity;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="WikiBundle\Entity\Category")
    * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
    */
    protected $category;

    /**
     * @var string
     *
     * @ORM\Column(name="view_count", type="integer", length=30)
     */
    private $viewCount;

    /**
     * @ORM\OneToMany(targetEntity="Revision", mappedBy="page", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Exclude
     */
    private $revisions;

    /**
     * @ORM\OneToMany(targetEntity="ContentImage", mappedBy="page", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Exclude
     */
    private $contentImages;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Page
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Page
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set category
     *
     * @param \WikiBundle\Entity\Category $category
     *
     * @return Page
     */
    public function setCategory(\WikiBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \WikiBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->viewCount = 0;
        $this->revisions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add revision
     *
     * @param \WikiBundle\Entity\Revision $revision
     *
     * @return Page
     */
    public function addRevision(\WikiBundle\Entity\Revision $revision)
    {
        $revision->setPage($this);
        $this->revisions[] = $revision;

        return $this;
    }

    /**
     * Remove revision
     *
     * @param \WikiBundle\Entity\Revision $revision
     */
    public function removeRevision(\WikiBundle\Entity\Revision $revision)
    {
        $this->revisions->removeElement($revision);
    }

    /**
     * Get revisions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRevisions()
    {
        return $this->revisions;
    }

    /**
     * Set viewCount
     *
     * @param integer $viewCount
     *
     * @return Page
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    /**
     * Get viewCount
     *
     * @return integer
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * Add contentImage
     *
     * @param \WikiBundle\Entity\ContentImage $contentImage
     *
     * @return Page
     */
    public function addContentImage(\WikiBundle\Entity\ContentImage $contentImage)
    {
        $contentImage->setPage($this);
        $this->contentImages[] = $contentImage;

        return $this;
    }

    /**
     * Remove contentImage
     *
     * @param \WikiBundle\Entity\ContentImage $contentImage
     */
    public function removeContentImage(\WikiBundle\Entity\ContentImage $contentImage)
    {
        $this->contentImages->removeElement($contentImage);
    }

    /**
     * Get contentImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContentImages()
    {
        return $this->contentImages;
    }
}
