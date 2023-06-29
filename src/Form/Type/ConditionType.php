<?php

// src/Form/Type/ShippingType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConditionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'conditions' => [
                'zniszczone' => 'destroyed',
                'używane' => 'used',
                'nowe' => 'new',
            ],
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}