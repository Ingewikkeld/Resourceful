<?php
/**
 * RestDistribution
 */

namespace Ingewikkeld\Rest\Resource;

use Symfony\Component\Form\FormBuilderInterface;

interface FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options);

    public function getName();

}
