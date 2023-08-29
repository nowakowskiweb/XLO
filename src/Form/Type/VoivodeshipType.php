<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoivodeshipType extends AbstractType
{
    public static function getVoivodeship(): array
    {
        return [
            'dolnoslaskie' => 'Dolnośląskie',
            'kujawsko-pomorskie' => 'Kujawsko-Pomorskie',
            'lubelskie' => 'Lubelskie',
            'lubuskie' => 'Lubuskie',
            'lodzkie' => 'Łódzkie',
            'malopolskie' => 'Małopolskie',
            'mazowieckie' => 'Mazowieckie',
            'opolskie' => 'Opolskie',
            'podkarpackie' => 'Podkarpackie',
            'podlaskie' => 'Podlaskie',
            'pomorskie' => 'Pomorskie',
            'slaskie' => 'Śląskie',
            'swietokrzyskie' => 'Świętokrzyskie',
            'warminsko-mazurskie' => 'Warmińsko-Mazurskie',
            'wielkopolskie' => 'Wielkopolskie',
            'zachodniopomorskie' => 'Zachodniopomorskie',
        ];
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}