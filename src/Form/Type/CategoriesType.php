<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesType extends AbstractType
{
    public static function getCategories(): array
    {
        return [
            [
                'name' => 'Elektronika',
                'description' => 'Znajdziesz tutaj szeroką gamę urządzeń elektronicznych od komputerów po smartfony. Odkryj najnowsze gadżety, akcesoria oraz najbardziej zaawansowane technologie dostępne na rynku.',
                'slug' => 'electronics'
            ],
            [
                'name' => 'Dom i Ogród',
                'description' => 'Czy szukasz mebli do swojego domu czy narzędzi do ogrodu? W tej kategorii znajdziesz wszystko, co potrzebujesz, aby uczynić swoje miejsce wyjątkowym.',
                'slug' => 'home-and-garden'
            ],
            [
                'name' => 'Moda',
                'description' => 'Odkryj najnowsze trendy w modzie, od odzieży po akcesoria. Czy jesteś pasjonatem mody, czy po prostu szukasz codziennych ubrań, tutaj znajdziesz coś dla siebie.',
                'slug' => 'fashion'
            ],
            [
                'name' => 'Sport i Rekreacja',
                'description' => 'Dla entuzjastów aktywnego trybu życia, ta kategoria oferuje szeroki wybór sprzętu sportowego, odzieży i akcesoriów. Poczuj radość z ruchu i odkryj nowe sposoby na spędzanie wolnego czasu.',
                'slug' => 'sports-and-recreation'
            ],
            [
                'name' => 'Motoryzacja',
                'description' => 'Pasja do pojazdów zaczyna się tutaj. Odkryj samochody, motocykle, części i akcesoria w jednym miejscu. Dla każdego miłośnika czterech i dwóch kółek.',
                'slug' => 'automotive'
            ],
            [
                'name' => 'Książki i Edukacja',
                'description' => 'Kultura i edukacja idą w parze. Odkryj bogaty wybór literatury, podręczników i innych materiałów edukacyjnych, które wzbogacą Twoją wiedzę i wyobraźnię.',
                'slug' => 'books-and-education'
            ],
            [
                'name' => 'Zdrowie i Uroda',
                'description' => 'Dbaj o siebie od wewnątrz i na zewnątrz. W tej kategorii znajdziesz kosmetyki, suplementy i inne produkty, które pomogą Ci czuć się i wyglądać wspaniale.',
                'slug' => 'health-and-beauty'
            ],
            [
                'name' => 'Sztuka i Kolekcje',
                'description' => 'Dla miłośników piękna i unikalnych przedmiotów. Odkryj dzieła sztuki, kolekcjonerskie znaczki, monety i wiele innych skarbów, które wzbogacą Twoją kolekcję.',
                'slug' => 'art-and-collectibles'
            ],
            [
                'name' => 'Rozrywka',
                'description' => 'Gdy potrzebujesz chwili relaksu, ta kategoria jest dla Ciebie. Znajdziesz tu gry, filmy, muzykę i bilety na różne wydarzenia, które dostarczą Ci niezapomnianych wrażeń.',
                'slug' => 'entertainment'
            ],
            [
                'name' => 'Żywność i Napoje',
                'description' => 'Odkryj smaki z całego świata. W tej kategorii znajdziesz produkty spożywcze, napoje oraz specjały ekologiczne, które zadowolą każde podniebienie.',
                'slug' => 'food-and-drinks'
            ],
            [
                'name' => 'Inne',
                'description' => 'Znajdź rzadkie, unikatowe lub nietypowe przedmioty, które nie mieszczą się w tradycyjnych kategoriach. Odkryj niespodzianki i skarby wśród różnorodnych ogłoszeń.',
                'slug' => 'others'
            ],
        ];
    }

    public static function getCategoriesAssociative(): array
    {
        $categories = self::getCategories();
        $associativeArray = [];

        foreach ($categories as $category) {
            $associativeArray[$category['name']] = $category['slug'];
        }

        return $associativeArray;
    }

    public static function mapCategories(array $slugs = []): array
    {
        $filteredCategories = array_filter(self::getCategories(), function($category) use ($slugs) {
            return in_array($category['slug'], $slugs);
        });

        $result = [];
        foreach ($filteredCategories as $category) {
            $result[$category['slug']] = $category['name'];
        }

        return $result;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}