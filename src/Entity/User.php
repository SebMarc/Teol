<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $society;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="integer")
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $technicien;

    private $message;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commande", mappedBy="tech")
     */
    private $tech;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commande", mappedBy="customer")
     */
    private $commandes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $encours;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Visite", mappedBy="user")
     */
    private $visites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Visite", mappedBy="tech")
     */
    private $techvisite;


    



      

    public function __toString()
    {
        //return $this->firstname . " " . $this->lastname;
        return $this->email;
        //return $this->society;
    }



    public function __construct()
    
    {
        $this->enable     = true;
        $this->commande = new ArrayCollection();
        $this->tech = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->visites = new ArrayCollection();
        $this->techvisite = new ArrayCollection();
        
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getSociety(): ?string
    {
        return $this->society;
    }

    public function setSociety(string $society): self
    {
        $this->society = $society;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTechnicien()
    {
        return $this->technicien;
    }

    public function setTechnicien(?User $technicien): self
    {
        $this->technicien = $technicien;

        return $this;
    }
  

    /**
     * Get the value of message
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getTech(): Collection
    {
        return $this->tech;
    }

    public function addTech(Commande $tech): self
    {
        if (!$this->tech->contains($tech)) {
            $this->tech[] = $tech;
            $tech->setTech($this);
        }

        return $this;
    }

    public function removeTech(Commande $tech): self
    {
        if ($this->tech->contains($tech)) {
            $this->tech->removeElement($tech);
            // set the owning side to null (unless already changed)
            if ($tech->getTech() === $this) {
                $tech->setTech(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setCustomer($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getCustomer() === $this) {
                $commande->setCustomer(null);
            }
        }

        return $this;
    }

    public function getEncours(): ?int
    {
        return $this->encours;
    }

    public function setEncours(int $encours): self
    {
        $this->encours = $encours;

        return $this;
    }

    /**
     * @return Collection|Visite[]
     */
    public function getVisites(): Collection
    {
        return $this->visites;
    }

    public function addVisite(Visite $visite): self
    {
        if (!$this->visites->contains($visite)) {
            $this->visites[] = $visite;
            $visite->setUser($this);
        }

        return $this;
    }

    public function removeVisite(Visite $visite): self
    {
        if ($this->visites->contains($visite)) {
            $this->visites->removeElement($visite);
            // set the owning side to null (unless already changed)
            if ($visite->getUser() === $this) {
                $visite->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Visite[]
     */
    public function getTechvisite(): Collection
    {
        return $this->techvisite;
    }

    public function addTechvisite(Visite $techvisite): self
    {
        if (!$this->techvisite->contains($techvisite)) {
            $this->techvisite[] = $techvisite;
            $techvisite->setTech($this);
        }

        return $this;
    }

    public function removeTechvisite(Visite $techvisite): self
    {
        if ($this->techvisite->contains($techvisite)) {
            $this->techvisite->removeElement($techvisite);
            // set the owning side to null (unless already changed)
            if ($techvisite->getTech() === $this) {
                $techvisite->setTech(null);
            }
        }

        return $this;
    }

    
}
