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

class Cart
{
    public $item_number;
    public $product_name;
    public $price;
    public $amount;
    public $item_inventory;
    public $category;
    public $path;
    public $totalAmount;
    public $totalAmountAfterSale;
    public $gender;

    public function __construct($item_number, $product_name, $price, $amount, $item_inventory, $category, $path, $gender)
    {
        $this->item_number = $item_number;
        $this->product_name = $product_name;
        $this->price = $price;
        $this->amount = $amount;
        $this->item_inventory = $item_inventory;
        $this->category = $category;
        $this->path = str_replace("pictureNumber", "1", $path);
        $this->totalAmount = round($price * $amount, 2);
        $this->totalAmountAfterSale = $this->calculateTotalAmountAfterSale($price, $amount);
        $this->gender = $gender;
    }

    public function calculateTotalAmountAfterSale($price, $amount)
    {
        $totalAmountAfterSale = 0;
        if ($amount >= 10) {
            $totalAmountAfterSale = round($amount * $price * 0.8, 2);
        } else if ($amount >= 5) {
            $totalAmountAfterSale = round($amount * $price * 0.9, 2);
        } else {
            $totalAmountAfterSale = round($amount * $price, 2);
        }
        return $totalAmountAfterSale;
    }

    public function getItemNumber()
    {
        return $this->item_number;
    }
    public function getProductName()
    {
        return $this->product_name;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function getAmount()
    {
        return $this->amount;
    }
    public function getItemInventory()
    {
        return $this->item_inventory;
    }
    public function getCategory()
    {
        return $this->category;
    }
    public function getPath()
    {
        return $this->path;
    }
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }
    public function getTotalAmountAfterSale()
    {
        return $this->totalAmountAfterSale;
    }

    public function getGender(){
        return $this->gender;
    }
}
