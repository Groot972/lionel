<?php

namespace App\Form;

use App\Entity\InfosCarosserie;
use App\Entity\ReparationCarosserie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfosCarosserieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('infos')
            ->add('reparation', EntityType::class, [
                'class' => ReparationCarosserie::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfosCarosserie::class,
        ]);
    }
}
