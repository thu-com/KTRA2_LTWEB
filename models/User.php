<?php
//  models/User.php

class User
{
    private int    $id;
    private string $name;
    private string $email;
    private string $password;  // hashed
    private string $role;
    private string $createdAt;

    public function __construct(array $data)
    {
        $this->id        = (int)   $data['id'];
        $this->name      = (string)$data['name'];
        $this->email     = (string)$data['email'];
        $this->password  = (string)$data['password'];
        $this->role      = (string)($data['role'] ?? 'customer');
        $this->createdAt = (string)($data['created_at'] ?? '');
    }

    public function verifyPassword(string $plain): bool
    {
        return password_verify($plain, $this->password);
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }

    // Getters 
    public function getId(): int    { return $this->id; }
    public function getName(): string  { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string  { return $this->role; }

    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
        ];
    }

    public static function hashPassword(string $plain): string
    {
        return password_hash($plain, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
