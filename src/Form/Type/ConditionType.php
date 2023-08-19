<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConditionType extends AbstractType
{
    public static function getConditions(): array
    {
        return [
            'new' => 'Nowy',
            'used' => 'Używany',
            'destroyed' => 'Zniszczony',
        ];
    }

    public static function getConditionKeys(): array
    {
        return array_keys(self::getConditions());
    }

    public static function mapConditions(array $keys): array
    {
        return array_filter(self::getConditions(), function($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}