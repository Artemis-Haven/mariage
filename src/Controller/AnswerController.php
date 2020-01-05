<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AnswerType;

/**
 * @Route("/reponse")
 */
class AnswerController extends AbstractController
{
    /**
     * @Route("/", name="answer")
     * @Template
     */
    public function index()
    {
        return [
            'guests' => $this->getUser()->getGuests()
        ];
    }



    /**
     * @Route("/formulaire", name="answer_form")
     * @Template
     */
    public function form(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(AnswerType::class, $user)
            ->add('submit', FormType\SubmitType::class, ['label' => 'Valider']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return [
            'form' => $form->createView()
        ];
    }
}