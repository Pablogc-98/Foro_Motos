<?php

namespace App\Entity;

use App\Repository\InteraccionesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InteraccionesRepository::class)]
class Interacciones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $favorito = null;

    #[ORM\Column(length: 255)]
    private ?string $comentario = null;

    #[ORM\ManyToOne(inversedBy: 'post')]
    private ?Post $post = null;

    #[ORM\ManyToOne(inversedBy: 'interaccion')]
    private ?Usuario $usuario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isFavorito(): ?bool
    {
        return $this->favorito;
    }

    public function setFavorito(bool $favorito): static
    {
        $this->favorito = $favorito;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario): static
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }
}
