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
    }



    /**
     * @Route("/formulaire", name="answer_form")
     */
    public function form(Request $request, \Swift_Mailer $mailer)
    {
        $user = $this->getUser();
        if ($this->getUser()->getGuests()->isEmpty()) {
            $email = (new \Swift_Message("Mariage - " . $user . " n'a aucun invité associé !"))
                ->setFrom($this->getParameter('mail_from'))
                ->setTo($this->getParameter('mail_from'))
                ->setBody($user . " n'a aucun invité associé !", 'text/html')
            ;
            $mailer->send($email);
            return $this->render('answer/no_guest.html.twig');
        }

        $form = $this->createAnswerForm();
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('submit')->isClicked()) {
                foreach ($user->getGuests() as $guest) {
                    $guest->setAnswered();
                }
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
            } elseif ($form->get('addGuest')->isClicked()) {
                $guest = new \App\Entity\Guest();
                $guest->setName("Nom de l'invité(e)");
                $this->getUser()->addGuest($guest);
                $form = $this->createAnswerForm();
            }
        }

        $template = 'answer/full_form.html.twig';
        if ($this->getUser()->isInvitedForCeremonyOnly()) {
            $template = 'answer/ceremony_only_form.html.twig';
        }
        return $this->render($template, [
            'form' => $form->createView()
        ]);
    }

    private function createAnswerForm()
    {
        $ceremonyOnly = $this->getUser()->isInvitedForCeremonyOnly();

        return $this->createForm(AnswerType::class, $this->getUser(), ['ceremonyOnly' => $ceremonyOnly])
            ->add('submit', FormType\SubmitType::class, ['label' => 'Valider'])
            ->add('addGuest', FormType\SubmitType::class, ['label' => 'Ajouter une personne']);
    }
}