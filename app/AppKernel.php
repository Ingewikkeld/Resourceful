<?php
/**
 * Symfony REST Edition.
 *
 * @link      https://github.com/Ingewikkeld/symfony-rest-edition
 * @copyright Copyright (c) 2013-2013 Ingewikkeld<info@ingewikkeld.net>
 * @license   https://github.com/Ingewikkeld/symfony-rest-edition/blob/master/LICENSE MIT License
 */

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Lunetics\LocaleBundle\LuneticsLocaleBundle(),
            new Ingewikkeld\Rest\OAuthServerBundle\IngewikkeldRestOAuthServerBundle(),
            new Ingewikkeld\Rest\UserBundle\IngewikkeldRestUserBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function getLogDir()
    {
        return '/var/log/symfony-rest';
    }

    public function getCacheDir()
    {
        return '/tmp/rest/cache';
    }
}
