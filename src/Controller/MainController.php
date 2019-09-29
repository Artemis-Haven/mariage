<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function index()
    {
        $form = $this->createFormBuilder()
            ->add('email', FormType\EmailType::class, ['required' => true, 'attr' => ['placeholder' => "Saisissez votre email"]])
            ->add('submit', FormType\SubmitType::class, ['label' => 'Tenez-moi au courant !'])
            ->getForm();

        return [
            'form' => $form->createView()
        ];
    }
}
