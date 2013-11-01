<?php
/**
 * Symfony REST Edition.
 *
 * @link      https://github.com/Ingewikkeld/symfony-rest-edition
 * @copyright Copyright (c) 2013-2013 Ingewikkeld
 * @license   https://github.com/Ingewikkeld/symfony-rest-edition/blob/master/LICENSE MIT License
 */

namespace Ingewikkeld\Rest\OAuthServerBundle\Command;

use Ingewikkeld\Rest\OAuthServerBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClientCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('oauth-server:create-client')
            ->setDescription('Creates a client for this oAuth server')
            ->addOption('grants', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, '', array('token', 'authorization_code'))
            ->addArgument('redirectUris', InputArgument::IS_ARRAY | InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');

        /** @var Client $client */
        $client = $clientManager->createClient();
        $client->setRedirectUris($input->getArgument('redirectUris'));
        $client->setAllowedGrantTypes($input->getOption('grants'));
        $clientManager->updateClient($client);

        $output->writeln('Generated a new client with id ' . $client->getPublicId());
    }
}
