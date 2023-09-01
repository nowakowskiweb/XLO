<?php

namespace App\Form;

use App\Entity\Announcement;
use App\Entity\Category;
use App\Form\Type\CategoriesType;

use App\Form\Type\ConditionType;
use App\Form\Type\VoivodeshipType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AddAnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Tytuł',
                'constraints' => [
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Your first name should have at least {{ max }} characters',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]+$/',
                        'message' => 'Your login should only contain letters and numbers.',
                    ]),
                    new NotBlank([
                        'message' => 'Please enter your title',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Opis',
                'constraints' => [
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Your first name should have at least {{ max }} characters',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]+$/',
                        'message' => 'Your login should only contain letters and numbers.',
                    ]),
                    new NotBlank([
                        'message' => 'Please enter your title',
                    ]),
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Cena',
                'constraints' => [
                    new NotBlank(['message' => 'Price should not be blank.']),
                    new Regex([
                        'pattern' => '/^\d+(\.\d{0,2})?$/',
                        'message' => 'The price should be a valid number with up to two decimals.',
                    ])
                ]
            ])
            ->add('voivodeship', ChoiceType::class, [
                'label' => 'Województwo',
                'choices' => array_flip(VoivodeshipType::getVoivodeship()),
                'constraints' => [
                    new Choice([
                        'choices' => array_keys(VoivodeshipType::getVoivodeship()),
                        'message' => 'Please select a valid condition.',
                    ]),
                    new NotBlank([
                        'message' => 'Please select a condition.',
                    ]),

                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Miasto',
                'constraints' => [
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Your city should have at least {{ max }} characters',
                    ]),
                    new NotBlank([
                        'message' => 'Please enter your city',
                    ]),
                ],
            ])
            ->add('conditionType', ChoiceType::class, [
                'label' => 'Stan',
                'choices' => array_flip(ConditionType::getConditions()),
                'constraints' => [
                    new Choice([
                        'choices' => array_keys(ConditionType::getConditions()),
                        'message' => 'Please select a valid condition.',
                    ]),
                    new NotBlank([
                        'message' => 'Please select a condition.',
                    ]),

                ],
            ])
            ->add('user', HiddenType::class, [
                'data' => $options['user'], // Przekazanie zalogowanego użytkownika
            ])
            ->add('category', EntityType::class, [
                'label' => 'Kategoria',
                'class' => Category::class,  // Ta klasa to twoja encja Category
                'choice_label' => 'name',   // To jest pole, które chcesz wyświetlić jako etykietę w formularzu
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a category.',
                    ]),
                ],
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'constraints' => [
                    new Assert\Callback(function ($images, ExecutionContextInterface $context) {
                        $nonEmptyImages = array_filter($images, function ($image) {
                            return null !== $image->getFile();
                        });
                        if (count($nonEmptyImages) === 0) {
                            $context->buildViolation('Proszę przesłać co najmniej jeden obraz.')
                                ->atPath('[0].file.vars.errors')
                                ->addViolation();
                        }
                    }),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Announcement::class,
            'user' => null, // Dodaj tę linię
        ]);
    }
}
