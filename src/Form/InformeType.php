<?php

namespace App\Form;

use App\Entity\Informe;
use App\Entity\Tour;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformeType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $guia=$this->security->getUser();
        $builder
            ->add('observaciones',TextEditorType::class)
            ->add('dinero',NumberType::class)
            ->add('imagen',FileType::class)
            ->add('Tour', EntityType::class, [
                'class' => Tour::class,
                'choice_label' => 'id',
                'query_builder' => function (EntityRepository $er) use ($guia) {
                    return $er->createQueryBuilder('t')
                        ->where('t.Guia = :Guia')
                        ->setParameter('Guia', $guia);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Informe::class,
        ]);
    }
}
