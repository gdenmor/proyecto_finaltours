<?php

namespace App\Form;

use App\Entity\Reserva;
use App\Entity\Tour;
use App\Entity\Valoracion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValoracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('estrellas')
            ->add('comentario')
            ->add('Reserva', EntityType::class, [
                'class' => Reserva::class,
'choice_label' => 'id',
            ])
            ->add('Tour', EntityType::class, [
                'class' => Tour::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Valoracion::class,
        ]);
    }
}
