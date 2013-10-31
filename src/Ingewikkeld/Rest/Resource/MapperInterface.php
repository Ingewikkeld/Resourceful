<?php
namespace Ingewikkeld\Rest\Resource;

use Hal\Resource as HalResource;

interface MapperInterface
{
    public function create(array $parameters);

    public function update(HalResource $resource);

    public function delete(HalResource $resource);

    public function createResourceFromObjects(array $objects);
}
