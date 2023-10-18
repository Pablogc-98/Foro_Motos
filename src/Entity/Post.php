<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    const TIPOS = ['🏁Circuito' => '🏁Circuito','👨🏼‍🔧Mecanica' => '👨🏼‍🔧Mecanica','🏍Rutas' => '🏍Rutas','😬Debate' => '😬Debate'];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(length: 500)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(length: 255)]
    private ?string $tipo = null;

    #[ORM\ManyToOne(inversedBy: 'usuario')]
    private ?Usuario $usuario = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Interacciones::class)]
    private Collection $post;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $archivo = null;

    public function __construct($titulo = null, $descripcion = null, $tipo = null, $fecha = null)
    {
        $this->titulo = $titulo; 
        $this->descripcion = $descripcion; 
        $this->tipo = $tipo; 
        $this->fecha =  new \DateTime; 
        $this->post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $Titulo): static
    {
        $this->titulo = $Titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $Descripcion): static
    {
        $this->descripcion = $Descripcion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

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

    /**
     * @return Collection<int, Interacciones>
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    public function addPost(Interacciones $post): static
    {
        if (!$this->post->contains($post)) {
            $this->post->add($post);
            $post->setPost($this);
        }

        return $this;
    }

    public function removePost(Interacciones $post): static
    {
        if ($this->post->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getPost() === $this) {
                $post->setPost(null);
            }
        }

        return $this;
    }

    public function getArchivo(): ?string
    {
        return $this->archivo;
    }

    public function setArchivo(string $archivo): static
    {
        $this->archivo = $archivo;

        return $this;
    }
}
