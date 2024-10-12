<?php

// if ((empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
//     && !defined('MY_APP')
//     && (empty($_SERVER['HTTP_CONTENT_TYPE']) || strtolower($_SERVER['HTTP_CONTENT_TYPE']) !== 'application/json')
// ) {
//     header("location: ../index.php");
// }else{
//     if(!defined('MY_APP')){
//         define('MY_APP', true);
//     }
// }

class Product
{
    public $item_number;
    public $product_name;
    public $path_img;
    public $price;
    public $item_inventory;
    public $category;
    public $gender;

    public function __construct($item_number, $product_name, $price, $item_inventory, $path_img, $category, $gender)
    {
        $this->item_number = $item_number;
        $this->product_name = $product_name;
        $this->path_img = $path_img;
        $this->price = $price;
        $this->item_inventory = $item_inventory;
        $this->category = $category;
        $this->gender = $gender;
    }

    public function getItemNumber()
    {
        return $this->item_number;
    }

    public function getProductName()
    {
        return $this->product_name;
    }

    public function getProductNameTrim()
    {
        $returnName = trim($this->product_name);
        return $returnName;
    }

    public function getPathImg($number)
    {
        $returnPath = str_replace("pictureNumber", $number, $this->path_img);
        return substr($returnPath, 1);
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getItemInventory()
    {
        return $this->item_inventory;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getGender()
    {
        return $this->gender;
    }
}
