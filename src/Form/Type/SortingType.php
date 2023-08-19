<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortingType extends AbstractType
{
    public static function getSorting(): array
    {
        return [
            'price_asc' => 'Cena od najniższej',
            'price_desc' => 'Cena od najwyższej',
        ];
    }

    public static function mapSorting(array $keys): array
    {
        return array_filter(self::getSorting(), function($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}