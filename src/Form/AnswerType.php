<?php

namespace App\Form;

use App\Entity\User;
use App\Form\GuestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('guests', FormType\CollectionType::class, [
                'entry_type' => GuestType::class,
                'entry_options' => ['ceremonyOnly' => $options['ceremonyOnly']]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'ceremonyOnly' => false
        ]);
    }
}
