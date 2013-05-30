<?php

namespace Profile\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserData
 */
class UserData
{
    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $birth;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var string
     */
    private $hobby;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var string
     */
    private $namedescription;

    /**
     * @var integer
     */
    private $icq;

    /**
     * @var string
     */
    private $myspace;

    /**
     * @var string
     */
    private $facebook;

    /**
     * @var string
     */
    private $googleplus;

    /**
     * @var string
     */
    private $homepage;

    /**
     * @var string
     */
    private $blog;

    /**
     * @var integer
     */
    private $userid;

    /**
     * @var \Profile\Entity\User
     */
    private $user;

    /**
     * Get user
     *
     * @return \Profile\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var string
     */
    private $picture;

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return UserData
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return UserData
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set birth
     *
     * @param \DateTime $birth
     * @return UserData
     */
    public function setBirth(\DateTime $birth)
    {
        $this->birth = $birth;

        return $this;
    }

    /**
     * Get birth
     *
     * @return \DateTime
     */
    public function getBirth()
    {
        return $this->birth;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return UserData
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set hobby
     *
     * @param string $hobby
     * @return UserData
     */
    public function setHobby($hobby)
    {
        $this->hobby = $hobby;

        return $this;
    }

    /**
     * Get hobby
     *
     * @return string
     */
    public function getHobby()
    {
        return $this->hobby;
    }

    /**
     * Set signature
     *
     * @param string $signature
     * @return UserData
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get signature
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set namedescription
     *
     * @param string $namedescription
     * @return UserData
     */
    public function setNamedescription($namedescription)
    {
        $this->namedescription = $namedescription;

        return $this;
    }

    /**
     * Get namedescription
     *
     * @return string
     */
    public function getNamedescription()
    {
        return $this->namedescription;
    }

    /**
     * Set icq
     *
     * @param integer $icq
     * @return UserData
     */
    public function setIcq($icq)
    {
        $this->icq = $icq;

        return $this;
    }

    /**
     * Get icq
     *
     * @return integer
     */
    public function getIcq()
    {
        return $this->icq;
    }

    /**
     * Set myspace
     *
     * @param string $myspace
     * @return UserData
     */
    public function setMyspace($myspace)
    {
        $this->myspace = $myspace;

        return $this;
    }

    /**
     * Get myspace
     *
     * @return string
     */
    public function getMyspace()
    {
        return $this->myspace;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     * @return UserData
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set googleplus
     *
     * @param string $googleplus
     * @return UserData
     */
    public function setGoogleplus($googleplus)
    {
        $this->googleplus = $googleplus;

        return $this;
    }

    /**
     * Get googleplus
     *
     * @return string
     */
    public function getGoogleplus()
    {
        return $this->googleplus;
    }

    /**
     * Set homepage
     *
     * @param string $homepage
     * @return UserData
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;

        return $this;
    }

    /**
     * Get homepage
     *
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * Set blog
     *
     * @param string $blog
     * @return UserData
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * Get blog
     *
     * @return string
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return UserData
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
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
     * Set user
     *
     * @param \Profile\Entity\User $user
     * @return UserData
     */
    public function setUser(\Profile\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return UserData
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * returns variables as an array
     * @return array
     */
    public function getArrayCopy()
    {
        $objectVars = get_object_vars($this);
        if ($objectVars['birth'] != null) {
            $objectVars['birth'] = $objectVars['birth']->format('d.m.Y');
        }
        return $objectVars;
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        $this->lastname = (isset($data['lastname'])) ? $data['lastname'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->gender = (isset($data['gender'])) ? $data['gender'] : null;
        $this->hobby = (isset($data['hobby'])) ? $data['hobby'] : null;
        $this->signature = (isset($data['signature'])) ? $data['signature'] : null;
        $this->namedescription = (isset($data['nameDescription'])) ? $data['nameDescription'] : null;
        $this->icq = (isset($data['icq'])) ? $data['icq'] : null;
        $this->myspace = (isset($data['myspace'])) ? $data['myspace'] : null;
        $this->facebook = (isset($data['facebook'])) ? $data['facebook'] : null;
        $this->googleplus = (isset($data['googleplus'])) ? $data['googleplus'] : null;
        $this->homepage = (isset($data['homepage'])) ? $data['homepage'] : null;
        $this->blog = (isset($data['blog'])) ? $data['blog'] : null;
        $this->picture = (isset($data['picture'])) ? $data['picture'] : null;
    }
}
