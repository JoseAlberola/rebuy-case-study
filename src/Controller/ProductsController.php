<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /*
    TO DO
        Listas todos los productos usando el metodo findAll() del repositorio de productos. 
    */
    #[Route(path: '/products', name: 'products', methods: ['GET'])]
    public function list(): Response
    {
        // return new Response('Welcome to Latte and Code ', Response::HTTP_CREATED);
        // $product = new Product();
        // $products = $product
        /*return new JsonResponse(
            [
                'message' => 'All products',
                'products' => [ 
                ]
            ], Response::HTTP_CREATED);*/
        return new Response('<h1 style="color:red">Hola don pepito</h1>');
    }

    /*
    TO DO
        - Descargar Postman
        - Ver como recoger los datos del producto de un objeto JSON en la peticion
        - Organizar el codigo entre Modelo y Controlador 
    */
    #[Route(path: '/products/new', name: 'create_product', methods: ['GET'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $response = new JsonResponse();
        $name = $request->get('name', null);
        $price = $request->get('price', null);
        if(!empty($name) && !empty($price)){
            $product->setName($name);
            $product->setPrice($price);
            $em->persist($product);
            $em->flush();
            $response->setData([
                'success' => true,
                'data' => [
                    [
                        'id' => $product->getId(),
                        'ean' => $product->getEan(),
                        'name' => $product->getName(),
                        'price' => $product->getPrice()
                    ]
                ]
            ]);
        }else {
            $error = "";
            if(empty($name)){
                $error .= "Name can not be empty. ";
            }
            if(empty($price)){
                $error .= "Price can not be empty. ";
            }
            $response->setData([
                'success' => false,
                'error' => $error,
                'data' => null
            ]);
        }
        return $response;
    }
}
