<?php

namespace App\libraries\Citrixoauth\Entity\Registrant;


/**
 * Registrant Entity
 *
 * Contains all fields for registratns and attendees. Consumer
 * is an entity that merges both registratns and attendees.
 */
class Post extends Registrant {

    /**
     * Feed data for creating a new Registrant
     *
     * @var array
     */
    protected $feed = [
        "firstName"    => null,
        "lastName"     => null,
        "email"        => null,
        "address"      => null,
        "city"         => null,
        "state"        => null,
        "zipCode"      => null,
        "country"      => null,
        "phone"        => null,
        "organization" => null,
    ];

    /**
     * Address
     *
     * @var string
     */
    private $address;

    /**
     * City
     *
     * @var string
     */
    private $city;

    /**
     * State
     *
     * @var string
     */
    private $state;

    /**
     * ZipCode
     *
     * @var string
     */
    private $zipCode;

    /**
     * Country
     *
     * @var string
     */
    private $country;

    /**
     * Phone
     *
     * @var string
     */
    private $phone;

    /**
     * Organization
     *
     * @var string
     */
    private $organization;

    /**
     * The Id of the registrant
     *
     * @var string
     */
    private $registrantKey;

    /**
     * The Url to Join
     *
     * @var string
     */
    private $joinUrl;

    public function getFeed() {
        return $this->feed;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCity() {
        return $this->city;
    }

    public function getState() {
        return $this->state;
    }

    public function getZipCode() {
        return $this->zipCode;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getOrganization() {
        return $this->organization;
    }

    public function getRegistrantKey() {
        return $this->registrantKey;
    }

    public function getJoinUrl() {
        return $this->joinUrl;
    }

    public function setFeed($feed) {
        $this->feed = $feed;
        return $this;
    }

    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }

    public function setCity($city) {
        $this->city = $city;
        return $this;
    }

    public function setState($state) {
        $this->state = $state;
        return $this;
    }

    public function setZipCode($zipCode) {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function setCountry($country) {
        $this->country = $country;
        return $this;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }

    public function setOrganization($organization) {
        $this->organization = $organization;
        return $this;
    }

    public function setRegistrantKey($registrantKey) {
        $this->registrantKey = $registrantKey;
        return $this;
    }

    public function setJoinUrl($joinUrl) {
        $this->joinUrl = $joinUrl;
        return $this;
    }

}
