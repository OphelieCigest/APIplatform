<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ApiResource()]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups (['read:Article'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:item','write:Article']),
        Length(min: 3)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Article::class)]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }
}
