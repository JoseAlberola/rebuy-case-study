<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Manufacturer;
use App\Repository\CategoryRepository;
use App\Repository\ManufacturerRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class ProductsController extends AbstractFOSRestController
{
    
    /**
     *
     * @Rest\Get(path="/products")
     * @Rest\View(serializerGroups={"product"}, serializerEnableMaxDepthChecks=true)
     */
    public function list(ProductRepository $productRepository)
    {
        return $productRepository->findAll();
    }

    /**
     *
     * @Rest\Post(path="/products")
     * @Rest\View(serializerGroups={"product"}, serializerEnableMaxDepthChecks=true)
     */
    public function create(ProductRepository $productRepository, CategoryRepository $categoryRepository, 
    ManufacturerRepository $manufacturerRepository, EntityManagerInterface $em)
    {
        $category = $categoryRepository->find(1);
        $manufacturer = $manufacturerRepository->find(1);
        $product = new Product();
        $varEan = array("1547", "4875", "123456789");
        $product->setEan($varEan);
        $product->setName("iPhone 14");
        $product->setPrice(987.8);
        $product->setCategory($category);
        $product->setManufacturer($manufacturer);
        $em->persist($product);
        $em->flush();
        return $product;
    }

}
