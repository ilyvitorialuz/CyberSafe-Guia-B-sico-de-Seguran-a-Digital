<?php

namespace App\Models;

class Contact
{
    public $id;
    public $name;
    public $email;
    public $message;
    public $category;
    public $created_at;

    public function __construct($name, $email, $message, $category = null, $id = null, $created_at = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
        $this->category = $category;
        $this->created_at = $created_at;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
            'category' => $this->category,
            'created_at' => $this->created_at
        ];
    }
}
