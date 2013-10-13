<?php
namespace Ingewikkeld\Rest\OAuthServerBundle\Resource;

use Doctrine\ORM\EntityManager;
use Ingewikkeld\Rest\OAuthServerBundle\Entity\Client as ClientEntity;

class Client
{
    public $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return ClientEntity
     */
    public function create()
    {

    }

    /**
     * @return void
     */
    public function update()
    {

    }

    /**
     * @return void
     */
    public function delete()
    {

    }

    /**
     *
     *
     * @param $key
     * @param $value
     *
     * @return ClientEntity
     */
    public function findBy($key, $value)
    {
        return new stdClass();
    }

    /**
     * @return ClientEntity[]
     */
    public function findAll()
    {
        $repo = $this->entityManager->getRepository('IngewikkeldRestOAuthServerBundle:Client');
        return $repo->findAll();
    }
}
