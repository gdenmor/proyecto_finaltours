<?php

namespace App\Form;

use App\Entity\Ruta;
use App\Entity\Tour;
use App\Entity\User;
use App\Repository\TourRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AsignaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo')
            ->add('fecha_inicio')
            ->add('fecha_fin')
            ->add('numplazas')
            ->add('Ruta', EntityType::class, [
                'class' => Ruta::class,
'choice_label' => 'id',
            ])
            ->add('Guia', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'query_builder' => function (TourRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('roles', '%ROLE_GUIA%'); // Reemplaza ROLE_GUIA con el rol deseado
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tour::class,
        ]);
    }
}
