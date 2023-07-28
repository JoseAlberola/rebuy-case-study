<?php

namespace App\Form\Model;

use App\Entity\Manufacturer;

class ManufacturerDTO {
    public $id;
    public $name;
    public $products;

    public function __construct()
    {
        $this->products = [];
    }

    public static function createFromManufacturer(Manufacturer $manufacturer) : self {
        $dto = new self();
        $dto->id = $manufacturer->getId();
        $dto->name = $manufacturer->getName();
        $dto->products = $manufacturer->getProducts();
        return $dto;
    }
}