<?php

namespace My\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use My\Entity\User;

/**
 * Description of UserRepository
 *
 * @author David Vartanian <david.vartanian@gointegro.com>
 */
class UserRepository extends EntityRepository
{
    public function getUsers($maxResults = 10, $firstResult = 0)
    {
        $dql = "SELECT u.id, u.firstname, u.lastname FROM My\Entity\User u";
        return $this->_em->createQuery($dql)
                ->setMaxResults($maxResults)
                ->setFirstResult($firstResult)
                ->getResult();
    }
    
    public function getUser($id)
    {
        return $this->find($id);
    }
    
    public function create($data)
    {
        $user = new User();
        
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        
        $this->_em->persist($user);
        $this->_em->flush();
        
        return $user;
    }
    
    /**
     * 
     * @param type $data
     * @param User $user
     * @return type
     */
    public function update($data, User $user)
    {
        if(!empty($data['firstname'])){
            $user->setFirstname($data['firstname']);
        }
        
        if(!empty($data['lastname'])){
            $user->setLastname($data['lastname']);
        }
        
        $this->_em->persist($user);
        $this->_em->flush();
        
        return $user;
    }
}