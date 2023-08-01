<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use App\Service\ProductFormProcessor;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
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
     * @Rest\Get(path="/products/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"product"}, serializerEnableMaxDepthChecks=true)
     */
    public function see_details(int $id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);
        if(!$product){
            throw $this->createNotFoundException('Product not found');
        }
        return $product;
    }

    /**
     *
     * @Rest\Post(path="/products")
     * @Rest\View(serializerGroups={"product"}, serializerEnableMaxDepthChecks=true)
     */
    public function create(ProductFormProcessor $productFormProcessor, Request $request)
    {
        [$product, $error] = ($productFormProcessor)($request);
        $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $product ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     *
     * @Rest\Put(path="/products/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"product"}, serializerEnableMaxDepthChecks=true)
     */
    public function edit(int $id, ProductFormProcessor $productFormProcessor, ProductRepository $productRepository, Request $request)
    {
        $product = $productRepository->find($id);
        if(!$product){
            return View::create('Product not found', Response::HTTP_BAD_REQUEST);
        }
        
        [$product, $error] = ($productFormProcessor)($request, $id);
        $statusCode = $product ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $product ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     *
     * @Rest\Delete(path="/products/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"product"}, serializerEnableMaxDepthChecks=true)
     */
    public function delete(int $id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);
        if(!$product){
            return View::create('Product not found', Response::HTTP_BAD_REQUEST);
        }
        $productRepository->remove($product, true);
        return View::create(null, Response::HTTP_NO_CONTENT);
    }

}
