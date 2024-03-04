<?php

namespace App\Form;

use App\Entity\Horario;
use App\Entity\Item;
use App\Entity\Localidad;
use App\Entity\Ruta;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RutaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo')
            ->add('descripcion')
            ->add('foto',FileType::class)
            ->add('fecha_inicio')
            ->add('fecha_fin')
            ->add('estado',ChoiceType::class,[
                'choices'=>[
                    'Cancelado'=>'Cancelado',
                    'Activado'=>'Activado'
                ]
            ])
            ->add('items', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('Localidad', EntityType::class, [
                'class' => Localidad::class,
                'choice_label' => 'nombre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ruta::class,
        ]);
    }
}
