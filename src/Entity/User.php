<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    public function getId(): ?int
    {
        return $this->id;
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

    //Fontion de la classe UserInterafce

    public function getRoles(){

        return ['ROLE_ADMIN'];

    }

    public function getSalt()
    {
        return null;
    }
    

    //permet de supprimer des informations qui serait sensibles
    public function eraseCredentials()
    {
        
    }

    //Methode de Serializable

    //transforme notre objet en chaine de caractère string
    public function serialize(){

        return serialize([
            $this->id,
            $this->username,
            $this->password

        ]);
    }

    // fait l'inverse
    //si on veut gener un utilisateur a partir de ces infos la 
    public function unserialize($serialized){

        list(
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
        //allowed_classes empeche l'instaciation de la classe dans la deserialisation
    }
    
}
