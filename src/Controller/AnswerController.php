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
     */
    public function form(Request $request, \Swift_Mailer $mailer)
    {
        $user = $this->getUser();
        $ceremonyOnly = $user->getGuests()->first()->isInvitedForCeremonyOnly();
        $form = $this->createForm(AnswerType::class, $user, ['ceremonyOnly' => $ceremonyOnly])
            ->add('submit', FormType\SubmitType::class, ['label' => 'Valider']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Votre réponse a bien été enregistrée !");
            $email = (new \Swift_Message("Mariage - " . $user . " a rempli le formulaire de réponse"))
                ->setFrom($this->getParameter('mail_from'))
                ->setTo($this->getParameter('mail_from'))
                ->setBody(
                    $this->renderView(
                        'emails/answer.html.twig',
                        [
                            'user' => $user
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($email);
            return $this->redirectToRoute('answer');
        }

        $template = 'answer/full_form.html.twig';
        if ($ceremonyOnly) {
            $template = 'answer/ceremony_only_form.html.twig';
        }
        return $this->render($template, [
            'form' => $form->createView()
        ]);
    }
}