<?php

namespace WikiBundle\Repository;

/**
 * RatingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RatingRepository extends \Doctrine\ORM\EntityRepository
{
	public function getAverageRatingByRevision($revision){
        $query = $this->createQueryBuilder('r')
        			  ->select('avg(r.rating) as avg_rating')
                      ->where('r.revision = :revision')
                      ->setParameter('revision', $revision);
        return $query->getQuery()->getSingleResult();
	}

	public function getRatingCountByRevision($revision){
        $query = $this->createQueryBuilder('r')
        			  ->select('count(r.rating) as avg_rating')
                      ->where('r.revision = :revision')
                      ->setParameter('revision', $revision);
        return $query->getQuery()->getSingleResult();
	}

	public function hasRateRevision($revision, $user){
        $query = $this->createQueryBuilder('r')
                      ->where('r.user = :user')
                      ->andWhere('r.revision = :revision')
                      ->setParameters(['revision' => $revision, 'user' => $user]);
        return $query->getQuery()->getOneOrNullResult();
	}
}
