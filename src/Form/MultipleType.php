<?php

namespace App\Form;

use App\Entity\Ruta;
use App\Entity\Tour;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultipleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tours', CollectionType::class, [
                'entry_type' => TourType::class, // Tipo de formulario para cada elemento de la colección
                'allow_add' => true, // Permite agregar nuevos elementos a la colección
                'allow_delete' => true, // Permite eliminar elementos de la colección
                'by_reference' => false, // Para que se trate como una colección de objetos
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'=>Tour::class
        ]);
    }
}
