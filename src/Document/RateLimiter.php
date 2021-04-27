<?php

namespace App\Document;


use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(collection="RateLimiter")
 * @MongoDBUnique(fields="ip")
 */
class RateLimiter
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $ip;

    /**
     * @MongoDB\Field(type="int")
     * @Assert\NotBlank()
     */
    protected $count;

    /**
     * @MongoDB\Field(type="int")
     * @Assert\NotBlank()
     */
    protected $lastRequestTimeStamp;


    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getIp(){
        return $this->ip;
    }

    public function setIp($ip){
        $this->ip = $ip;
    }

    public function getCount(){
        return $this->count;
    }

    public function setCount($count){
        $this->count = $count;
    }

    public function getLastRequestTimeStamp(){
        return $this->lastRequestTimeStamp;
    }

    public function setLastRequestTimeStamp($lastRequestTimeStamp){
        $this->lastRequestTimeStamp = $lastRequestTimeStamp;
    }
}