<?php

namespace WebLinks\Domain;

use  \Symfony\Component\Security\Core\User\UserInterface;
/**
 * Description of User
 *
 * @author demoniakillah
 */
class User implements UserInterface{
    
    /**
     * User id.
     *
     * @var Integer
     */
    private $id;
    
    /**
     * User username.
     *
     * @var String
     */
    private $username;
    
    /**
     * User password.
     *
     * @var String
     */
    private $password;
    
    /**
     * User salt.
     *
     * @var String
     */
    private $salt;
    
    /**
     * User role.
     * 
     * Values : ROLE_USER or ROLE_ADMIN
     * @var String
     */
    private $role;
    
    public function __construct($userData=NULL) {
        $this->id=$userData['user_id'];
        $this->name=$userData['user_name'];
        $this->role=$userData['user_role'];
    }
    
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getRole() {
        return $this->role;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUsername($name) {
        $this->username = $name;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setSalt($salt) {
        $this->salt = $salt;
    }

    public function setRole($role) {
        $this->role = $role;
    }
    
    /**
    * @inheritDoc
    */
    public function getRoles()
    {
        return array($this->getRole());
    }

    /**
    * @inheritDoc
    */
    public function eraseCredentials() {
        // Nothing to do here
    }
}
