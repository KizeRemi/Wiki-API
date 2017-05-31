<?php

namespace WikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation\Exclude;

/**
 * Rating
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="WikiBundle\Repository\CommentRepository")
 */
class Comment
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
     * @var int
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
    * @ORM\ManyToOne(targetEntity="WikiBundle\Entity\User")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    protected $user;

    /**
    * @ORM\ManyToOne(targetEntity="WikiBundle\Entity\Page")
    * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
    * @Exclude()
    */
    protected $page;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param integer $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return integer
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set user
     *
     * @param \WikiBundle\Entity\User $user
     *
     * @return Comment
     */
    public function setUser(\WikiBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \WikiBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set page
     *
     * @param \WikiBundle\Entity\Page $page
     *
     * @return Comment
     */
    public function setPage(\WikiBundle\Entity\Page $page = null)
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
