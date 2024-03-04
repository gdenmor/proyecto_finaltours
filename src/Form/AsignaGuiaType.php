<?php

namespace App\Form;

use App\Entity\Horario;
use App\Entity\Ruta;
use App\Entity\Tour;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HorarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Dia',ChoiceType::class,[
                'choices' => [
                    'Lunes' => 'Lunes',
                    'Martes' => 'Martes',
                    'Miércoles' => 'Miércoles',
                    'Jueves' => 'Jueves',
                    'Viernes' => 'Viernes',
                    'Sábado' => 'Sábado',
                    'Domingo' => 'Domingo',
                ],
                'label' => 'Día',
            ])
            ->add('Hora_Inicio',TimeType::class,[
                'input' => 'string'
            ])
            ->add('Hora_Fin',TimeType::class,[
                'input' => 'string'
            ])
            ->add('Ruta', EntityType::class, [
                'class' => Ruta::class,
                'choice_label' => 'id',
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
