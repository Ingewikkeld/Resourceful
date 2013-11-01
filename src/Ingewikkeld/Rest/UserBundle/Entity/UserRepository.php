<?php
/**
 * Symfony REST Edition.
 *
 * @link      https://github.com/Ingewikkeld/symfony-rest-edition
 * @copyright Copyright (c) 2013-2013 Ingewikkeld<info@ingewikkeld.net>
 * @license   https://github.com/Ingewikkeld/symfony-rest-edition/blob/master/LICENSE MIT License
 */

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
