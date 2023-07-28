<?php

namespace App\Form\Model;

use App\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO {
    public $ean = [];
    #[Assert\NotBlank]
    public $name;
    #[Assert\NotBlank]
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