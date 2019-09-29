<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Subscription;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function index(Request $request)
    {
        $sub = new Subscription();
        $message = null;

        $form = $this->createFormBuilder($sub)
            ->add('email', FormType\EmailType::class, ['required' => true, 'attr' => ['placeholder' => "Saisissez votre email"]])
            ->add('submit', FormType\SubmitType::class, ['label' => 'Tenez-moi au courant !'])
            ->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $message = "Merci, c'est noté !";
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($sub);
            $em->flush();
        }

        return [
            'message' => $message,
            'form' => $form->createView()
        ];
    }
}
