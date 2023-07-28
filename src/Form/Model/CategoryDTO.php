<?php

namespace App\Form\Model;

use App\Entity\Category;

class CategoryDTO {
    public $id;
    public $name;
    public $products;

    public function __construct()
    {
        $this->products = [];
    }

    public static function createFromCategory(Category $category) : self {
        $dto = new self();
        $dto->id = $category->getId();
        $dto->name = $category->getName();
        $dto->products = $category->getProducts();
        return $dto;
    }
}