<?php

namespace App\libraries\Citrixoauth\Entity\Webinar;

use App\libraries\Citrixoauth\Entity\Webinar;

class Post extends Webinar {

    /**
     * Data for feeding the request.
     *
     * @var array
     */
    protected $feed = [
        "subject"             => null,
        "description"         => null,
        "times"               => [[
            "startTime" => null,
            "endTime"   => null]],
        "timeZone"            => null,
        "type"                => null,
        "isPasswordProtected" => null
    ];

    /**
     * Webinar type
     *
     * @var string
     */
    private $type = 'single_session';

    /**
     * @var boolean
     */
    private $isPasswordProtected = false;

    /**
     * The Id of the webinar.
     * This datum came from the response object with hydrate().
     *
     * @var string
     */
    private $webinarKey;


    public function getType() {
        return $this->type;
    }

    public function getIsPasswordProtected() {
        return $this->isPasswordProtected;
    }

    public function getWebinarKey() {
        return $this->webinarKey;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setIsPasswordProtected($isPasswordProtected) {
        $this->isPasswordProtected = $isPasswordProtected;
        return $this;
    }

    public function setWebinarKey($webinarKey) {
        $this->webinarKey = $webinarKey;
        return $this;
    }


}
