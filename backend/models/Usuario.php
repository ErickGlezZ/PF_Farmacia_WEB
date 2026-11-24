<?php

namespace App\Models;

class Usuario
{
    private ?int $idUsuario;
    private string $nombre;
    private string $usuario;
    private string $passwordHash;
    private string $rol;
    private int $estado;
    private ?string $fechaCreacion;

    public function __construct(
        ?int $idUsuario = null,
        string $nombre = '',
        string $usuario = '',
        string $passwordHash = '',
        string $rol = 'Empleado',
        int $estado = 1,
        ?string $fechaCreacion = null
    ) {
        $this->idUsuario = $idUsuario;
        $this->nombre = $nombre;
        $this->usuario = $usuario;
        $this->passwordHash = $passwordHash;
        $this->rol = $rol;
        $this->estado = $estado;
        $this->fechaCreacion = $fechaCreacion;
    }

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(?int $idUsuario): void
    {
        $this->idUsuario = $idUsuario;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getUsuario(): string
    {
        return $this->usuario;
    }

    public function setUsuario(string $usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    public function getEstado(): int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): void
    {
        $this->estado = $estado;
    }

    public function getFechaCreacion(): ?string
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(?string $fechaCreacion): void
    {
        $this->fechaCreacion = $fechaCreacion;
    }
}
