<?php

namespace WikiBundle\Repository;

/**
 * PageRevisionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RevisionRepository extends \Doctrine\ORM\EntityRepository
{
	public function getLatestOnlineRevisionByPage($page){
		$query = $this  ->createQueryBuilder('r')
						->where('r.page = :page')
						->andWhere('r.status = 1')
					    ->orderBy('r.id', 'ASC')
					    ->setMaxResults(1)
					    ->setParameter('page', $page)
					    ->getQuery();
		return $query->getSingleResult();
	}
	public function hasAlreadyPendingRevisionByPage($page, $user){
		$query = $this  ->createQueryBuilder('r')
						->where('r.page = :page')
						->andWhere('r.status = 2')
						->andWhere('r.user = :user')
					    ->setParameters(['page' => $page, 'user' => $user])
					    ->getQuery();
		return $query->getOneOrNullResult();
	}
}
