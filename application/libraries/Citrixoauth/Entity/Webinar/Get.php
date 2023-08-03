<?php

namespace App\libraries\Citrixoauth\Entity\Webinar;

use App\libraries\Citrixoauth\Entity\Webinar;

/**
 * Description of Get
 *
 * @author DalPraS
 */
class Get extends Webinar {

    /**
     * Data for feeding the request.
     *
     * @var array
     */
    protected $feed = [];

    /**
     * The Id of the webinar.
     *
     * @var string
     */
    private $webinarKey;

    /**
     * The Id of the organizer.
     *
     * @var string
     */
    private $organizerKey;

    /**
     * Url to which is possible to subscribe,
     *
     * @var string
     */
    private $registrationUrl;

    public function getWebinarKey() {
        return $this->webinarKey;
    }

    public function getOrganizerKey() {
        return $this->organizerKey;
    }

    public function getRegistrationUrl() {
        return $this->registrationUrl;
    }

    public function setWebinarKey($webinarKey) {
        $this->webinarKey = $webinarKey;
        return $this;
    }

    public function setOrganizerKey($organizerKey) {
        $this->organizerKey = $organizerKey;
        return $this;
    }

    public function setRegistrationUrl($registrationUrl) {
        $this->registrationUrl = $registrationUrl;
        return $this;
    }

}
