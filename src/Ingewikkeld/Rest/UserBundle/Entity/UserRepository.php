<?php
namespace Ingewikkeld\Rest\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * @method User findOneByUsername()
 * @method User findOneByUsernameCanonical()
 */
class UserRepository extends EntityRepository
{
}
