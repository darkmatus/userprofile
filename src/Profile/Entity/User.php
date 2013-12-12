<?php

namespace Profile\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 */
class User
{
    /**
     * @var \DateTime
     */
    private $register;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $displayname;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $username;

    /**
     * @var integer
     */
    private $userid;

    /**
     * @var string
     */
    private $token;

    /**
     * @var integer
     */
    private $login;

    /**
     * @var \Profile\Entity\UserData
     */
    private $userdata;

    /**
     * @var integer
     */
    private $isAdmin;

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set displayname
     *
     * @param string $displayname
     * @return User
     */
    public function setDisplayname($displayname)
    {
        $this->displayname = $displayname;

        return $this;
    }

    /**
     * Get displayname
     *
     * @return string
     */
    public function getDisplayname()
    {
        return $this->displayname;
    }

    /**
     * get Register
     * @return \DateTime
     */
    public function getRegister()
    {
        return $this->register;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get userid
     *
     * @return integer
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set userdata
     *
     * @param \Profile\Entity\UserData $userdata
     * @return User
     */
    public function setUserdata(\Profile\Entity\UserData $userdata = null)
    {
        $this->userdata = $userdata;

        return $this;
    }

    /**
     * Get userdata
     *
     * @return \Profile\Entity\UserData
     */
    public function getUserdata()
    {
        return $this->userdata;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set login
     *
     * @param integer $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return integer
     */
    public function getLogin()
    {
        return $this->login;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function setIsAdmin($admin)
    {
        $this->isAdmin = $admin;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data)
    {
        $this->username    = $data['username'];
        $this->password    = $data['password'];
        $this->email       = $data['email'];
        $this->city        = $data['city'];
        $this->displayname = $data['displayname'];
    }
}
