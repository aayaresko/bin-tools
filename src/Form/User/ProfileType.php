<?php

namespace App\Form\User;

use FOS\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    public function getParent()
    {
        return ProfileFormType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('isVisible', CheckboxType::class, ['label' => 'user.is_visible', 'required' => false]);
    }
}
