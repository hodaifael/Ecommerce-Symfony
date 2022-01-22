<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 * @ApiResource()
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $codep;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="float")
     */
    private $pu;

    /**
     * @ORM\Column(type="float")
     */
    private $pht;

    /**
     * @ORM\Column(type="integer")
     */
    private $qt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typ;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodep(): ?int
    {
        return $this->codep;
    }

    public function setCodep(int $codep): self
    {
        $this->codep = $codep;

        return $this;
    }
    
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPu(): ?float
    {
        return $this->pu;
    }

    public function setPu(float $pu): self
    {
        $this->pu = $pu;

        return $this;
    }

    public function getPht(): ?float
    {
        return $this->pht;
    }

    public function setPht(float $pht): self
    {
        $this->pht = $pht;

        return $this;
    }

    public function getQt(): ?int
    {
        return $this->qt;
    }

    public function setQt(int $qt): self
    {
        $this->qt = $qt;

        return $this;
    }

    public function getTyp(): ?string
    {
        return $this->typ;
    }

    public function setTyp(string $typ): self
    {
        $this->typ = $typ;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function __toString(): string
    {
        return $jsonContent = $serializer->serialize($this, 'json');
    }
}
