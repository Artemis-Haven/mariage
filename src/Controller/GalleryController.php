<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/albums-photos")
 */
class GalleryController extends AbstractController
{
    /**
     * @Route("/", name="gallery")
     * @Template
     */
    public function index()
    {
    	$em = $this->getDoctrine()->getManager();
        return [
            "galleries" => $em->getRepository(Gallery::class)->findAll(),
        ];
    }
    
    /**
     * @Route("/nouvel-album", name="gallery_new")
     * @Template
     */
    public function newGallery(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$newGallery = new Gallery();

        $form = $this->createForm(\App\Form\GalleryType::class, $newGallery, ['new' => true]);

    	$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newGallery->setCreatedAt(new \DateTime('now'));
            $newGallery->setCreator($this->getUser());
	        $em->persist($newGallery);
	        $em->flush();

	        return $this->redirectToRoute('gallery_upload_photos', ['id' => $newGallery->getId()]);
	    }
        return ['form' => $form->createView()];
    }
    
    /**
     * @Route("/modifier-album-{id}", name="gallery_edit")
     * @Template
     */
    public function editGallery(Request $request, Gallery $gallery)
    {
        $form = $this->createForm(\App\Form\GalleryType::class, $gallery);
    	$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        $this->getDoctrine()->getManager()->flush();

	        return $this->redirectToRoute('gallery_show', ['id' => $gallery->getId()]);
        }
        
        return [
            'gallery' => $gallery,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/album-{id}/ajouter-des-photos", name="gallery_upload_photos")
     * @Template
     */
    public function uploadPhotos(Gallery $gallery)
    {
    }

    /**
     * @Route("/album-{id}/upload-process", name="gallery_upload_process")
     */
    public function processUpload(Request $request, Gallery $gallery)
    {
        $em = $this->getDoctrine()->getManager();
        // @todo access control
        // @todo input validation
        $files = $request->files->get('file');

        /** @var UploadedFile $file */
        foreach ($files as $file) {
            $photo = new Photo();
            $photo->setImageFile($file);
            $photo->setCreatedAt(new \DateTime('now'));
            $photo->setImageName($file->getClientOriginalName());
            $gallery->addPhoto($photo);
            $em->persist($photo);
        }

        $em->flush();

        return new JsonResponse([
            'success'     => true,
            'redirectUrl' => $this->generateUrl(
                'gallery_show',
                ['id' => $gallery->getId()]
            ),
        ]);
    }

    /**
     * @Route("/album-{id}", name="gallery_show")
     * @Template
     */
    public function showGallery(Gallery $gallery)
    {
    }

    /**
     * @Route("/photo-{id}", name="gallery_photo_show")
     * @Template
     */
    public function showPhoto(Photo $photo)
    {
    }

    /**
     * @Route("/supprimer-photo-{id}", name="gallery_photo_delete")
     */
    public function deletePhoto(Photo $photo)
    {
        $gallery = $photo->getGallery();
        if ($this->getUser() != $gallery->getCreator()) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer cette photo.');

            return $this->redirectToRoute('gallery_show', ['id' => $gallery->getId()]);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();

        $this->addFlash('success', 'Photo supprimée');

        return $this->redirectToRoute('gallery_edit', ['id' => $gallery->getId()]);
    }
}