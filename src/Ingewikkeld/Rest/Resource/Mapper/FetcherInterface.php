<?php
/**
 * Symfony REST Edition.
 *
 * @link      https://github.com/Ingewikkeld/symfony-rest-edition
 * @copyright Copyright (c) 2013-2013 Ingewikkeld<info@ingewikkeld.net>
 * @license   https://github.com/Ingewikkeld/symfony-rest-edition/blob/master/LICENSE MIT License
 */

namespace Ingewikkeld\Rest\Resource\Mapper;

use Hal\Resource as HalResource;
use Symfony\Component\Form\FormInterface;

interface FetcherInterface
{
    /**
     * @param string|int $identifier
     *
     * @return HalResource
     */
    public function getResource($identifier);

    /**
     * @param string[] $options
     *
     * @return HalResource
     */
    public function getCollection(array $options = array());
}
