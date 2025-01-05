<?php

namespace App\DTO;

use Symfony\Component\Mime\Message;
use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{
    #[Assert\Length(min:0,max:30, maxMessage:"Trop Long!!!!")]
    public string $name="";
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email="";
    #[Assert\NotBlank]
    #[Assert\Length(min:2,max:500, maxMessage:"Trop Long!!!!")]
    public string $message="";

    public string $autre="";
    #[Assert\NotIdenticalTo(
        value: 0,
    )]
    public string $honeyPot="";
}