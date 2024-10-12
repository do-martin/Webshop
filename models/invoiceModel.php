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

class Invoice
{
    public $id_invoice_number;
    public $order_date;
    public $shipping_company;
    public $total_amount;
    public $ordered_articles;
    public $shipping_price;
    public $used_points;
    public $subtotal;
    public $total_without_used_points_and_promo_code;

    public function __construct($id_invoice_number, $order_date, $shipping_company, $total_amount, $ordered_articles, $shipping_price, $used_points, $total_without_used_points_and_promo_code)
    {
        $this->id_invoice_number = $id_invoice_number;
        if (!is_int($order_date)) {
            $order_date = strtotime($order_date);
        }
        $this->order_date = date('l, d.m.Y', $order_date);
        $this->shipping_company = $shipping_company;
        $this->total_amount = $total_amount;
        $this->ordered_articles = $ordered_articles;
        $this->shipping_price = $shipping_price;
        $this->used_points = $used_points;
        foreach($ordered_articles as $article){
            $this->subtotal += $article->getTotalAmount();
        }
        $this->subtotal += sprintf("%.2f",$shipping_price);
        $this->total_without_used_points_and_promo_code = $total_without_used_points_and_promo_code;
    }

    public function getIdInvoiceNumber()
    {
        return $this->id_invoice_number;
    }

    public function getOrderDate()
    {
        return $this->order_date;
    }

    public function getShippingCompany()
    {
        return $this->shipping_company;
    }

    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    public function getOrderedArticles()
    {
        return $this->ordered_articles;
    }

    public function setOrderedArticles($ordered_articles)
    {
        $this->ordered_articles = $ordered_articles;
    }

    public function getShippingPrice()
    {
        return $this->shipping_price;
    }

    public function getUsedPoints()
    {
        return $this->used_points;
    }

    public function getSubtotal(){
        return $this->subtotal;
    }

    public function getCleanTotal(){
        return $this->total_without_used_points_and_promo_code;
    }
}
