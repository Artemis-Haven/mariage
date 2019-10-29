<?php

namespace App\Form;

use App\Entity\Gift;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class GiftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['splittable']) {
            $builder
                ->add('amount', FormType\TextType::class, [
                    'label' => 'Montant de votre contribution'
                ])
            ;
        }
        $builder
            ->add('message', null, [
                'label' => 'Vous pouvez ajouter un petit message si vous le souhaitez.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gift::class,
            'splittable' => true
        ]);
    }
}
