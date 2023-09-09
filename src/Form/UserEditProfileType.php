<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserEditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'Login',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a login',
                    ]),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Your login should be at most {{ limit }} characters long',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9_\-]+$/',
                        'message' => 'Your login should only contain letters, numbers, underscores, and dashes.',
                    ]),
                ],
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxWidth' => 700,
                        'maxHeight' => 700,
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'The image file is too large. Maximum allowed size is {{ limit }} MB.',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Invalid image format. Allowed formats are: {{ types }}.',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
