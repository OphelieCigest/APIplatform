<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource(

    normalizationContext: ['groups' => ['read:collection']],
    denormalizationContext: ['groups'=> ['write:Article']],
    paginationItemsPerPage: 2,
    paginationMaximumItemsPerPage:2,
    paginationClientItemsPerPage:true,//l'utilisateur pourra pilotter les choses
    collectionOperations: [
        'get',
        'post'
    ],
    itemOperations: [
        'put' ,
        'delete',
        'get' => [
            'normalization_context' => ['groups' => ['read:collection','read:item', 'write:Article']]
            ]
        ]
        ),
ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'title' =>'partial'])
]
//On va utider des filtres ci dessus pour  Lister nos Articles
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
    #[Groups(['read:item','write:Article'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:item'])]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedDate = null;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'articles', cascade: ['ALL'])]
    #[Groups(['read:item','write:Article']),
    Valid()]
    // on va preciser que la category est visible
    private ?Categorie $category;
    
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
