<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\TextBlock;

class TextProvider
{
    /**
     *
     * @var EntityManager 
     */
    protected $em;

    /**
     *
     * @var EntityRepository 
     */
    protected $repo;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->repo = $entityManager->getRepository(TextBlock::class);
    }

    public function __call($method, $arguments)
    {
        $text = $this->repo->findOneByName($method);
        if ($text) {
            return trim($text->getContent()); 
        } else {
            $textBlock = new TextBlock($method, '');
            $this->em->persist($textBlock);
            $this->em->flush();
            return '';
        }
    }
}
