<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonneRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints  as Assert;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[UniqueEntity(
    fields: ['tel', 'nom'],
    errorPath: 'tel',
    message: 'Un animateur existe déja avec ce numéro{{ value }}',
)]
    

// #[ORM\Entity(name:"Personne")]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type", type: "string")]
#[ORM\DiscriminatorMap(["personne" => "Personne", "usager" => "Usager", "animateur" => "Animateur"])]

class Personne 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('usager:read')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    #[Groups('usager:read')]
    private $nom;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    #[Groups('usager:read')]
    private $prenom;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank]
    #[Groups('usager:read')]
    private $tel;

    public function __toString()
    {
        return $this->getNom() .' '. $this->getPrenom();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = strtoupper($nom);

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = ucfirst(strtolower($prenom));

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

}
