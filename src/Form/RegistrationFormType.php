<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class)
            ->add('password', PasswordType::class, [
                'mapped' => true,
                'attr' => ['autocomplete' => 'new-password'],
            ])
            ->add('telefono',NumberType::class,[
                'mapped'=>true
            ])
            ->add('username',TextType::class,[
                'mapped'=>true,
                'required'=>false
            ])
            ->add('apellido1',TextType::class,[
                'mapped'=>true
            ])
            ->add('apellido2',TextType::class,[
                'mapped'=>true])
            ->add('foto',FileType::class,[
                'mapped'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
