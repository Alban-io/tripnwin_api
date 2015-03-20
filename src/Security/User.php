<?php

namespace TripNWin\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private $id;
    private $email;
    private $password;

    public function __construct($id, $email, $password)
    {
        $this->id       = $id;
        $this->email    = $email;
        $this->password = $password;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}