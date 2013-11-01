<?php
namespace Ingewikkeld\Rest\OAuthServerBundle\Form;

use Ingewikkeld\Rest\Resource\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class ClientType extends AbstractType implements FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'redirectUris',
                'collection',
                array(
                    'required' => true,
                    'type'     => 'url',
                    'constraints' => new All(
                        array(
                            'constraints' => array(
                                new NotBlank(),
                                new Url()
                            )
                        )
                    )
                )
            )
            ->add(
                'grants',
                'choice',
                array(
                    'required' => false,
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
