<?php

namespace WikiBundle\Rating;

use Doctrine\ORM\EntityManager;
use WikiBundle\Entity\Rating;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\JsonResponse;

class RatingManager
{
	const MAX_NOTE = 5;
	const MIN_NOTE = 0;

	protected $em;
	private $rating;
	private $user;
	private $tokenStorage;

	public function __construct(EntityManager $em, TokenStorage $tokenStorage){
		$this->em = $em;
		$this->rating = new Rating();
		$this->tokenStorage = $tokenStorage;
	}

	public function checkStatus($revision){
		$status = $revision->getStatus()->getId();
		if($status != 1){
			return false;
		} else {
			return true;
		}
	}

	public function addRating($revision, $note){
		$user = $this->tokenStorage->getToken()->getUser();
		$rating = $this->rating;
		$rating->setRevision($revision);
		$rating->setRating($note);
		$rating->setUser($user);
		$this->em->persist($rating);
		$this->em->flush();
	}

	public function updateRating($rating, $note){
		$rating->setRating($note);
		$this->em->persist($rating);
		$this->em->flush();
	}
	public function checkNote($note){
		if($note >= self::MIN_NOTE && $note <= self::MAX_NOTE){
			return true;
		} else {
			return false;
		}
	}
	public function hasRateRevision($revision, $user){
		$hasRate = $this->em->getRepository('WikiBundle:Rating')->hasRateRevision($revision, $user);
		if($hasRate){
			return true;
		} else {
			return false;
		}
	}
}