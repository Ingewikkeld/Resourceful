<?php
namespace Ingewikkeld\Rest\Resource;

use Hal\Resource as HalResource;

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
     * @param string[] $parameters
     *
     * @return HalResource
     */
    public function create(array $parameters);

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
    }
