<?php

namespace App\libraries\Citrixoauth\Entity;

/**
 * Description of Registrant
 *
 * @author DalPraS
 */
class Registrant extends EntityAbstract {

    /**
     * Data for feeding the request.
     *
     * @var array
     */
    protected $feed = [
        "firstName" => null,
        "lastName"  => null,
        "email"     => null,
    ];

    /**
     * First Name
     *
     * @var string
     */
    private $firstName;

    /**
     * Last Name
     *
     * @var string
     */
    private $lastName;

    /**
     * Email Address
     *
     * @var string
     */
    private $email;

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

}
