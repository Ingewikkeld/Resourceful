<?php
/**
 * Symfony REST Edition.
 *
 * @link      https://github.com/Ingewikkeld/symfony-rest-edition
 * @copyright Copyright (c) 2013-2013 Ingewikkeld
 * @license   https://github.com/Ingewikkeld/symfony-rest-edition/blob/master/LICENSE MIT License
 */

namespace Ingewikkeld\Rest\Resource;

use Hal\Resource as HalResource;
use Hal\Resource;
use Symfony\Component\Form\FormInterface;

interface MapperInterface
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

    /**
     * @param FormInterface $parameters
     *
     * @return HalResource
     */
    public function create(FormInterface $form);

    /**
     * @param HalResource $resource
     *
     * @return void
     */
    public function update(HalResource $resource);

    /**
     * @param HalResource $resource
     *
     * @return void
     */
    public function delete(HalResource $resource);

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

    public function populateResourceWithForm(Resource $resource, FormInterface $form);
}
