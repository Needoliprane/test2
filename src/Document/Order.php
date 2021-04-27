<?php

namespace App\Document;


use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(collection="Order")
 */
class Order
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $userId;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $products;

    /**
     * @MongoDB\Field(type="float")
     * @Assert\NotBlank()
     */
    protected $totalPrice;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $creationDate;

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function setUserId($userId){
        $this->userId = $userId;
    }

    public function getProducts(){
        return $this->products;
    }

    public function setProducts($products){
        $this->products = $products;
    }

    public function getTotalPrice(){
        return $this->totalPrice;
    }

    public function setTotalPrice($totalPrice){
        $this->totalPrice = $totalPrice;
    }

    public function getCreationDate(){
        return $this->creationDate;
    }

    public function setCreationDate($creationDate){
        $this->creationDate = $creationDate;
    }
}