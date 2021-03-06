<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface; 
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *  fields={"email"}, 
 * message="The email address idicated is already being used !"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caracteres")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas tapez le meme mot de passe")
     */
    public $confirm_password;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="owner")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

   public function eraseCredentials(){}
   public function getSalt(){}
   public function getRoles(){
       return ['ROLE_USER'];
   }

   /**
    * @return Collection|Article[]
    */
   public function getArticles(): Collection
   {
       return $this->articles;
   }

   public function addArticle(Article $article): self
   {
       if (!$this->articles->contains($article)) {
           $this->articles[] = $article;
           $article->setOwner($this);
       }

       return $this;
   }

   public function removeArticle(Article $article): self
   {
       if ($this->articles->removeElement($article)) {
           // set the owning side to null (unless already changed)
           if ($article->getOwner() === $this) {
               $article->setOwner(null);
           }
       }

       return $this;
   }
}
