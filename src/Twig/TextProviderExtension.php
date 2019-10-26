<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\TextBlock;

class TextProviderExtension extends AbstractExtension
{
    /**
     * @var EntityManager 
     */
    protected $em;

    /**
     * @var EntityRepository 
     */
    protected $repo;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->repo = $entityManager->getRepository(TextBlock::class);
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('text', [$this, 'getText'], ['is_safe' => ['html']]),
        ];
    }

    public function getText($name)
    {
        $text = $this->repo->findOneByName($name);
        if ($text) {
            return $text->getContent(); 
        } else {
            $textBlock = new TextBlock($name, '');
            $this->em->persist($textBlock);
            $this->em->flush();
            return '';
        }
    }
}