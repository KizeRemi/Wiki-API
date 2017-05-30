<?php

namespace WikiBundle\Counter;

use Doctrine\ORM\EntityManager;
use WikiBundle\Entity\Page;

class CounterViewPage
{
	protected $em;

	public function __construct(EntityManager $em){
		$this->em = $em;
	}

	public function addView(Page $page) {
		$viewCount = $page->getViewCount();
		$page->setViewCount($viewCount+1);
        $this->em->persist($page);
        $this->em->flush();
	}

	public function getRepository(){
		return $this->em->getRepository('WikiBundle:Page');
	}
}