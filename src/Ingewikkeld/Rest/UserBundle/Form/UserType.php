<?php
namespace Ingewikkeld\Rest\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array('constraints' => array(new NotBlank())))
            ->add('email', 'email', array('constraints' => array(new Email(), new NotBlank())))
            ->add('password', 'password', array('constraints' => array(new Length(array('min' => 6)))));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'user';
    }
}
