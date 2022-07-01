<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('marque')
            ->add('model')
            ->add('description')
            ->add('imageForm', FileType::class, [
                "mapped" => false,
                "required" => false,
                'attr' => [
                'class' =>'dropify',
                'id' =>'input-file-now-costom-1',
                'for' =>'input-file-now-costom-1'
                ]
            ])
            ->add('prixjournalier')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
