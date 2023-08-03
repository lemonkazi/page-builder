<?php

namespace App\libraries\Citrixoauth\Entity;

class Webinar extends EntityAbstract {

    protected $feed = [
        "subject"             => null,
        "description"         => null,
        "times"               => [[
            "startTime" => null,
            "endTime"   => null]],
        "timeZone"            => null
    ];

    /**
     * Subject of the Webinar.
     *
     * @var string
     */
    private $subject;

    /**
     * Description of the webinar.
     *
     * @var string
     */
    private $description;

    /**
     * Start datetime of the webinar.
     *
     * @var \DateTime
     */
    private $startTime;

    /**
     * Ending Datetime of the webinar.
     *
     * @var \DateTime
     */
    private $endTime;


    public function getSubject() {
        return $this->subject;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getStartTime() {
        return $this->startTime;
    }

    public function getEndTime() {
        return $this->endTime;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setStartTime(\DateTime $startTime) {
        $this->startTime = $startTime;
        return $this;
    }

    public function setEndTime(\DateTime $endTime) {
        $this->endTime = $endTime;
        return $this;
    }
}
