<?php

namespace App\Citrixoauth\Entity;

/**
 * Provides common functionality used accross the codebase.
 * If you inherit from this class and override the $toArray with the map needed
 * in $params parameter. give you the correct assignment for calling the citrix
 * methods.
 */
abstract class EntityAbstract {

    /**
     * Array mapping schema, when calling feed() became data.
     * This property "feed" the request
     *
     * @var array
     */
    protected $feed = [];

    /**
     * Current timezone
     *
     * @var string
     */
    private $timeZone = 'Europe/Rome';

    /**
     * Converts class variables to array for feeding the Citrix platform.
     *
     * @return array
     */
    public function feed() {
        $timeZone = new \DateTimeZone('UTC');
        array_walk_recursive($this->feed, function(&$value, $key) use ($timeZone) {
            $getter = 'get' . ucfirst($key);
            if (method_exists($this, $getter)) {
                $getterValue = $this->$getter();
                switch (true) {
                    case $getterValue instanceof \DateTime:
                        $value = $getterValue->setTimezone($timeZone)->format('Y-m-d\TH:i:s\Z');
                        break;
                    case is_array($getterValue):
                        break;
                    default:
                        $value = $getterValue;
                }
            }
        });
        return $this->feed;
    }

    /**
     * Hydrate the object properties with data coming from Citrix.
     * Date in Citrix are in UTC format.
     */
    public function hydrate($data) {
        array_walk_recursive($data, function($value, $key) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                switch (true) {
                    case preg_match('~^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z~i', $value):
                        $date = \DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $value, new \DateTimeZone('UTC'));
                        $date->setTimeZone(new \DateTimeZone($this->timeZone));
                        $this->$setter($date);
                        break;
                    case is_array($value):
                        break;
                    default:
                        $this->$setter($value);
                }
            }
        });
        return $this;
    }

    /**
     * @return the timeZone
     */
    public function getTimeZone() {
        return $this->timeZone;
    }

    /**
     * @param string $timeZone
     * @return $this
     */
    public function setTimeZone($timeZone) {
        $this->timeZone = $timeZone;
        return $this;
    }

}
