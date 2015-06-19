<?php

namespace Ingewikkeld\Resourceful\PrototypeBundle\Commands;

use Webmozart\Assert\Assert;

class ProjectionCommand
{
    /** @var string */
    private $url;

    /** @var string */
    private $name;
    /**
     * @var array
     */
    private $options;

    public function __construct($url, $name, array $options = [])
    {
        Assert::string($url);
        Assert::string($name);
        Assert::isArray($options);

        $this->url     = $url;
        $this->name    = $name;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getProjectionName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
