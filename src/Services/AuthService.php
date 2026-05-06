<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Exceptions\BusinessRuleException;

class AuthService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register($name, $email, $password)
    {
        if ($this->repository->findByEmail($email)) {
            throw new BusinessRuleException("E-mail já cadastrado.");
        }

        if (strlen($password) < 6) {
            throw new BusinessRuleException("Senha muito curta (mín. 6 caracteres).");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        return $this->repository->save([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ]);
    }

    public function login($email, $password)
    {
        $user = $this->repository->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            throw new BusinessRuleException("E-mail ou senha incorretos.");
        }

        // Remove password before returning
        unset($user['password']);
        return $user;
    }
}
