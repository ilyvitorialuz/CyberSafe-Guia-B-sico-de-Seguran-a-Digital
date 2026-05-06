<?php

namespace App\Models;

class User
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $created_at;

    public function __construct($name, $email, $password, $id = null, $created_at = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = $created_at;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'created_at' => $this->created_at
        ];
    }
}
