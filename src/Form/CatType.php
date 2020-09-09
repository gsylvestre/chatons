<?php

namespace App\Form;

use App\Entity\Cat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class CatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('picture', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new Image([
                        //'minWidth' => 1080,
                        'maxSize' => '10000k',
                        'maxSizeMessage' => 'Trop gros ton chat'
                    ])
                ],
            ])
            ->add('description')
            ->add('price')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cat::class,
        ]);
    }
}
