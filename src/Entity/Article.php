<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource(
    //attributes: [
        //'validation_groups' =>[]
   // ],
    normalizationContext: ['groups' => ['read:collection']],
    denormalisation: ['groups'=> ['write:Article']],
    collectionOperations: [
        'get',
        'post' =>[
            'validation_groups' =>[Article::class, 'validationGroups']
            // La par rapport à avant je vais utilser la classe qui permet de renvoyer à des groupes de validation
            // on vas cree des groupes de validation ici par exemple la contrainte de longueur du titre sera definie
            //uniquement à la création de mon Article
        ]
    ],



    itemOperations: [
        'put' ,
        //=> [
        //     //je veux que mon utilisateur puisse modifier uniquement le titre c'est donc lui qui rentre des donnees c'est la denormalisation
        //     'desormalization_context' => ['groups' => ['put:Article']]
        // ],
        'delete',
        'get' => [
            'normalization_context' => ['groups' => ['read:collection','read:item', 'read:Article']]
        ]
    ]

class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:collection', 'put:Article']),
        Length(min: 2, groups:['create:Article'])
    ]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:collection'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:item'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:item'])]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedDate = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[Groups(['read:item','put:Article'])]// on va preciser que la category est visible
    private ?Categorie $category = null;
    
    public function __construct(){
        $this->createdDate = new \DateTime();
        $this->updatedDate = new \DateTime();

    }
    //fonction permettant d'appliquer des conditions specifiques
    public static function validationGroups(self $article){
        return['create:Article'];
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(\DateTimeInterface $updatedDate): self
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    public function getCategory(): ?Categorie
    {
        return $this->category;
    }

    public function setCategory(?Categorie $category): self
    {
        $this->category = $category;

        return $this;
    }
}
