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

interface PersistenceInterface
{
    /**
     * @param FormInterface $form
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
     * Populates the provided resource with the fields from the given form.
     *
     * @param HalResource   $resource
     * @param FormInterface $form
     *
     * @return void
     */
    public function populateResourceWithForm(HalResource $resource, FormInterface $form);
}
