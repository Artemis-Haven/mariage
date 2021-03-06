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
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        if ($this->getParameter('coming_soon')) {
            return $this->comingSoon($request, $mailer);
        } elseif (!$this->getUser()) {
            return $this->render('index.html.twig');
        }
        
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/validation", name="waiting_for_validation")
     * @Template
     */
    public function waitingForValidation(\Swift_Mailer $mailer)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(\App\Entity\User::class)->findOneBy([], ['id' => 'DESC']);
        $email = (new \Swift_Message("Mariage - " . $user . " a créé son compte."))
            ->setFrom($this->getParameter('mail_from'))
            ->setTo($this->getParameter('mail_from'))
            ->setBody(
                $this->renderView(
                    'emails/new_account_alert.html.twig', 
                    [
                        'user' => $user
                    ]
                ),
                'text/html'
            );
        $mailer->send($email);

        return [];
    }
    
    /**
     * @Route("/hebergements", name="accommodations")
     * @Template
     */
    public function accommodations()
    {
        $em = $this->getDoctrine()->getManager();
        return [
            'accommodations' => $em->getRepository(\App\Entity\Accommodation::class)->findAll()
        ];
    }
    
    /**
     * @Route("/plan-acces", name="access_map")
     * @Template
     */
    public function accessMap()
    {
        return [];
    }
    
    /**
     * @Route("/contactez-nous", name="contact")
     * @Template
     */
    public function contact()
    {
        return [];
    }

    /**
     * @Route("/bientot", name="coming_soon")
     */
    public function comingSoon(Request $request, \Swift_Mailer $mailer)
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

            $email = (new \Swift_Message("Mariage - Quelqu'un a laissé son adresse e-mail"))
                ->setFrom($this->getParameter('mail_from'))
                ->setTo($this->getParameter('mail_from'))
                ->setBody(
                    $this->renderView(
                        'emails/subscription.html.twig',
                        [
                            'newSub' => $sub,
                            'allSubs' => $em->getRepository(Subscription::class)->findAll()
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($email);
        }

        return $this->render('main/coming-soon.html.twig', [
            'message' => $message,
            'form' => $form->createView()
        ]);
    }
}
