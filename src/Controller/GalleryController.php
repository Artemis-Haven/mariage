<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/reponse")
 */
class GalleryController extends AbstractController
{
    /**
     * @Route("/albums-photos", name="gallery")
     * @Template
     */
    public function index()
    {
        return [];
    }
}