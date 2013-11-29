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
use Hal\Resource;
use Symfony\Component\Form\FormInterface;

interface UrlGeneratorInterface
{
    /**
     * @return string
     */
    public function generateBrowseUrl();

    /**
     * @param HalResource|string|integer $resourceOrIdentifier
     *
     * @return string
     */
    public function generateReadUrl($resourceOrIdentifier);
}
