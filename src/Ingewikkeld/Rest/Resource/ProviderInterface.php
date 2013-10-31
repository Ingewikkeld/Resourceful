<?php
namespace Ingewikkeld\Rest\Resource;

interface ProviderInterface
{
    public function getResource($identifier);

    public function getCollection(array $options = array());

    public function generateBrowseUrl();

    public function generateReadUrl($resourceOrIdentifier);
}
