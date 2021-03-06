<?php

namespace App\Form;

use App\Entity\User;
use App\Form\GuestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('guests', FormType\CollectionType::class, [
                'entry_type' => GuestType::class,
                'entry_options' => ['ceremonyOnly' => $options['ceremonyOnly']],
                'allow_add' => true
            ])
        ;
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                
                $user = $event->getData();
                foreach ($user->getGuests() as $guest) {
                    $guest->addUser($user);
                    if ($guest->getId() == null) {
                        $guest->setInvitedForCeremonyOnly($user->isInvitedForCeremonyOnly());
                    }
                }
                
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'ceremonyOnly' => false
        ]);
    }
}
