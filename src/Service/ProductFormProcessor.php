<?php

namespace App\Service;

use App\Entity\Product;
use App\Form\Model\ProductDTO;
use App\Form\Type\ProductFormType;
use App\Repository\CategoryRepository;
use App\Repository\ManufacturerRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductFormProcessor
{

    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    private ManufacturerRepository $manufacturerRepository;
    private FormFactoryInterface $formFactory;

    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        ManufacturerRepository $manufacturerRepository,
        FormFactoryInterface $formFactory
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->manufacturerRepository = $manufacturerRepository;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request, ?string $productId = null): array
    {
        $productDTO = null;
        $product = new Product();

        if($productId === null){
            $productDTO = ProductDTO::createEmpty();
        }else{
            $product = $this->productRepository->find($productId);        
            $productDTO = ProductDTO::createFromProduct($product);
        }
        
        $form = $this->formFactory->create(ProductFormType::class, $productDTO);
        $form->handleRequest($request);
    
        if(!$form->isSubmitted()){
            return [null, new Response('Form is not submitted', Response::HTTP_BAD_REQUEST)];
        }

        $category = $this->categoryRepository->find($productDTO->category->id ?? 0);
        if(!$category){
            $errorCategory = new FormError("Category does not exist");
            $form->addError($errorCategory);
        }
        $manufacturer = $this->manufacturerRepository->find($productDTO->manufacturer->id ?? 0);
        if(!$manufacturer){
            $errorManufacturer = new FormError("Manufacturer does not exist");
            $form->addError($errorManufacturer);
        }
        
        if($form->isValid()){
            $product->setEan($productDTO->ean);
            $product->setName($productDTO->name);
            $product->setPrice($productDTO->price);
            $product->setCategory($category);
            $product->setManufacturer($manufacturer);
            $this->productRepository->save($product, true);
            return [$product, null];
        }
        return [null, $form];
    }
}