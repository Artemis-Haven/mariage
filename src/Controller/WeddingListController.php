<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use App\Entity\ListItem;
use App\Entity\Gift;
use App\Form\GiftType;

/**
 * @Route("/liste-de-mariage")
 */
class WeddingListController extends AbstractController
{
    /**
     * @Route("/", name="wedding_list")
     * @Template
     */
    public function index(UploaderHelper $uploaderHelper)
    {
        $em = $this->getDoctrine()->getManager();
    	$listItems = $em->getRepository(ListItem::class)->findBy([], ['ordering' => 'ASC']);
    	$markerList = [];
        $polyline = [];
        
    	foreach ($listItems as $item) {
    		$markerList[] = [
                'contribute_url' => $this->generateUrl('wedding_list_contribute', ['id' => $item->getId()]),
    			'title' => $item->getTitle(),
    			'description' => $item->getDescription(),
    			'ordering' => $item->getOrdering(),
    			'latitude' => (float) $item->getLatitude(),
    			'longitude' => (float) $item->getLongitude(),
    			'price' => $item->getPrice(),
    			'image' => $uploaderHelper->asset($item, 'imageFile'),
                'alreadyFundedAmount' => $item->getAlreadyFundedAmount()
    		];
    		$polyline[] = [(float) $item->getLatitude(), (float) $item->getLongitude()];
        }
        
        return [
        	'listItems' => $listItems,
        	'markerList' => $markerList,
        	'polyline' => $polyline,
    	];
    }

    /**
     * @Route("/version-classique", name="wedding_list_classic")
     * @Template
     */
    public function classic()
    {
        $em = $this->getDoctrine()->getManager();

        return [
            'listItems' => $em->getRepository(ListItem::class)->findBy([], ['ordering' => 'ASC'])
        ];
    }

    /**
     * @Route("/participer-{id}", name="wedding_list_contribute")
     */
    public function contribute(Request $request, ListItem $item)
    {
        $em = $this->getDoctrine()->getManager();
        $newGift = new Gift();
        $newGift->setListItem($item);
        $newGift->setGiver($this->getUser());
        if (!$item->isSplittable()) {
            $newGift->setAmount($item->getPrice());
        }

        $newGiftForm = $this->createForm(GiftType::class, $newGift, ['splittable' => $item->isSplittable()])
            ->add('submit', FormType\SubmitType::class, array('label' => 'Valider'))
        ;

        $newGiftForm->handleRequest($request);

        if ($newGiftForm->isSubmitted() && $newGiftForm->isValid()) {
            $newGift->setCreatedAt(new \Datetime('now'));
            $em->persist($newGift);
            if ($item->getAlreadyFundedAmount() == $item->getPrice()) {
                $item->setGifted(true);
            }
            $em->flush();

            return $this->render('wedding_list/thank_you.html.twig',
                ['gift' => $newGift]
            );
        }
        return $this->render('wedding_list/contribute.html.twig', [
            'item' => $item,
            'form' => $newGiftForm->createView()
        ]);
    }
}