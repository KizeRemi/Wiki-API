<?php
namespace WikiBundle\Counter;

use Doctrine\ORM\EntityManager;
use WikiBundle\Entity\Page;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class CounterViewPage
{
	protected $em;
	private $session;
	private $request;
	private $token;
	public function __construct(EntityManager $em, RequestStack $request){
		$this->em = $em;
		$this->request = $request;
	}

	public function addView(Page $page) {
		$this->getTokenHeader();

		$this->createSession();
		if(!$this->checkAlreadyViewPage($page)){
			$this->addTokenInSession($page);
			$viewCount = $page->getViewCount();
			$page->setViewCount($viewCount+1);
	        $this->em->persist($page);
	        $this->em->flush();			
		}
	}

	private function getTokenHeader(){
		$headers = $this->request->getCurrentRequest()->headers->all();
        $this->token = $headers['authorization'];
	}
	private function createSession(){
		$storage = new NativeSessionStorage();
		$storage->setOptions(array('cookie_lifetime' => 1000));
		$this->session = new Session($storage);	
	}

	private function checkAlreadyViewPage($currentPage){
		$pages = $this->session->get('view_count');
		foreach($pages as $page){
			if($page == $currentPage->getId()){
				return true;
			}
		}
		return false;
	}

	private function addTokenInSession($page){
		$pages = $this->session->get('view_count');
		if(count($viewCount) == 0 ){
			$this->session->set('view_count', []);
		}
		$pages[] = $page->getId();
		$this->session->set('view_count', $pages);
		
	}



	public function getRepository(){
		return $this->em->getRepository('WikiBundle:Page');
	}
}