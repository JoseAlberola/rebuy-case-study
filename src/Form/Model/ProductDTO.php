<?php

namespace App\Form\Model;

use App\Entity\Product;

class ProductDTO {
    public $ean = [];
    public $name;
    public $price;
    public $category;
    public $manufacturer;

    public static function createFromProduct(Product $product) : self {
        $dto = new self();
        $dto->ean = $product->getEan();
        $dto->name = $product->getName();
        $dto->price = $product->getPrice();
        $dto->category = CategoryDTO::createFromCategory($product->getCategory());
        $dto->manufacturer = ManufacturerDTO::createFromManufacturer($product->getManufacturer());
        return $dto;
    }
}