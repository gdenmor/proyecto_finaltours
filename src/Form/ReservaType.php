<?php

namespace App\Form;

use App\Entity\Reserva;
use App\Entity\Tour;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('fecha_reserva')
            /*->add('estado',TypeTextType::class,[
                //'data' => 'ValorPredeterminado'
            ])*/
            ->add('num_personas',NumberType::class,[
                'data'=>'1'
            ])
            /*->add('Usuario', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])*/
            /*->add('tour', EntityType::class, [
                'class' => Tour::class,
                'choice_label' => 'id',
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reserva::class,
        ]);
    }
}
