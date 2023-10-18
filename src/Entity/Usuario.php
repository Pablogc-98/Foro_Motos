<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $foto = null;

    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Post::class)]
    private Collection $usuario;

    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Interacciones::class)]
    private Collection $interaccion;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;


    public function __construct($username = null, $password = null, $foto = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->foto = $foto;
        $this->usuario = new ArrayCollection();
        $this->interaccion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(string $Foto): static
    {
        $this->foto = $Foto;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getUsuario(): Collection
    {
        return $this->usuario;
    }

    public function addUsuario(Post $usuario): static
    {
        if (!$this->usuario->contains($usuario)) {
            $this->usuario->add($usuario);
            $usuario->setUsuario($this);
        }

        return $this;
    }

    public function removeUsuario(Post $usuario): static
    {
        if ($this->usuario->removeElement($usuario)) {
            // set the owning side to null (unless already changed)
            if ($usuario->getUsuario() === $this) {
                $usuario->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Interacciones>
     */
    public function getInteraccion(): Collection
    {
        return $this->interaccion;
    }

    public function addInteraccion(Interacciones $interaccion): static
    {
        if (!$this->interaccion->contains($interaccion)) {
            $this->interaccion->add($interaccion);
            $interaccion->setUsuario($this);
        }

        return $this;
    }

    public function removeInteraccion(Interacciones $interaccion): static
    {
        if ($this->interaccion->removeElement($interaccion)) {
            // set the owning side to null (unless already changed)
            if ($interaccion->getUsuario() === $this) {
                $interaccion->setUsuario(null);
            }
        }

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }


}
