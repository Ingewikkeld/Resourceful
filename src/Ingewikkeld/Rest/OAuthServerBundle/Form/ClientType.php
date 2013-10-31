<?php
namespace Ingewikkeld\Rest\OAuthServerBundle\Form;

use Ingewikkeld\Rest\Resource\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ClientType extends AbstractType implements FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('redirectUris', 'collection', array('type' => 'url'))
            ->add(
                'grants',
                'choice',
                array(
                     'choices' => array(
                         'token' => 'token',
                         'authorization_code' => 'authorization_code'
                     ),
                     'multiple' => true
                )
            );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'client';
    }
}
