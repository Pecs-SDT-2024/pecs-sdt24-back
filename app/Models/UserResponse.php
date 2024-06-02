<?php

namespace App\Models;

/**
 * Reponse model with user data.
 */
class UserResponse {
    /**
     * User's name
     * @var string
     */
    public $name;

    /**
     * User's email address
     */
    public $email;

    /**
     * @param User $user
     */
    public function __construct($user)
    {
        $this->name = $user->name;
        $this->email = $user->email;
    }
}
