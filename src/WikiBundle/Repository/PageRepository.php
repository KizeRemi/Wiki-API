<?php

namespace WikiBundle\Repository;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends \Doctrine\ORM\EntityRepository
{
	public function getPagesByCategoryWithOffsetAndLimit($category, $offset, $limit){
        $query = $this->createQueryBuilder('p')
                      ->where('p.category = :category')
                      ->setParameter('category', $category)
                      ->orderBy('p.id', 'ASC');
        if ($offset != "") {
            $query->setFirstResult($offset);
        }
        if ($limit != "") {
            $query->setMaxResults($limit);
        }
        return $query->getQuery()->getResult();
	}
}
