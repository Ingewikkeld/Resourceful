<?php
/**
 * RestDistribution
 */

namespace Ingewikkeld\Rest\OAuthServerBundle\Resource;

use Ingewikkeld\Rest\OAuthServerBundle\Entity\Client as ClientEntity;

class Client
{
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
        return array();
    }
}
