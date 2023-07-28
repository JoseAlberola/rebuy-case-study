<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Form\Model\ProductDTO;
use App\Form\Type\ProductFormType;
use App\Repository\CategoryRepository;
use App\Repository\ManufacturerRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    public function create(EntityManagerInterface $em, Request $request)
    {
        $productDTO = new ProductDTO();
        $form = $this->createForm(ProductFormType::class, $productDTO);
        $form->handleRequest($request);
        if(!$form->isSubmitted()){
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        if($form->isValid()){
            $product = Product::createFromProductDTO($productDTO);
            $em->persist($product);
            $em->flush();
            return $product;
        }
        return $form;
    }

    /**
     *
     * @Rest\Post(path="/products/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"product"}, serializerEnableMaxDepthChecks=true)
     */
    public function edit(int $id, EntityManagerInterface $em, ProductRepository $productRepository, CategoryRepository $categoryRepository,
    ManufacturerRepository $manufacturerRepository, Request $request){
        $product = $productRepository->find($id);
        if(!$product){
            throw $this->createNotFoundException('Product not found');
        }
        $productDTO = ProductDTO::createFromProduct($product);
        
        $form = $this->createForm(ProductFormType::class, $productDTO);
        $form->handleRequest($request);
    
        if(!$form->isSubmitted()){
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        if($form->isValid()){
            $product->setEan($productDTO->ean);
            $product->setName($productDTO->name);
            $product->setPrice($productDTO->price);
            $product->setCategory($categoryRepository->find($productDTO->category->id));
            $product->setManufacturer($manufacturerRepository->find($productDTO->manufacturer->id));
            $em->persist($product);
            $em->flush();
            return $product;
        }
        return $form;
    }

}
