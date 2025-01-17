<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Profil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $photo;

    #[ORM\Column(type: 'text', nullable: true)]
    private $bio;

    #[ORM\OneToOne(inversedBy: 'profil', targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getPhoto(){
        return $this->photo;
    }

    public function setPhoto($photo){
        $this->photo = $photo;
    }

    public function getBio(){
        return $this->bio;
    }

    public function setBio($bio){
        $this->bio = $bio;
    }

    
    public function getUser(){
        return $this->user;
    }


    public function setUser(User $user){
        $this->user = $user;
    }
}   
