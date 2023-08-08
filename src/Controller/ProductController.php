<?php

namespace App\Controller;

Use Symfony\Component\Routing\Annotation\Route;
Use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
Use Doctrine\ORM\EntityManagerInterface;
Use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProductTypePhpType;
use App\Tax\CalculTva;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;

class ProductController extends AbstractController  
{

    #[Route('/product', name: 'app_product')]
    public function index(EntityManagerInterface $entityManager ){

        $product = $entityManager->getRepository(Product::class)->findBy(['valid' => true]);
    //dump('test');
        return $this->render('avecMateriel.html.twig', [
            'products' => $product
        ]);

    }

    #[Route('/product/new', name: 'app_product_new')]
    public function new(Request $request, EntityManagerInterface $manager, CalculTva $calculTva ){

        $product = new Product();

        $form = $this->createForm(ProductTypePhpType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setValid(true);

            $product->setPrice($calculTva->CalculTTC($product->getPrice()));

            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Produit créer avec succès ! produit : '.$product->getName());

            return $this->redirectToRoute('app_product');
        }

        return $this->render('new.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
    

    #[Route('/product/{id}', name: 'app_product_show')]
    public function show($id, EntityManagerInterface $entityManager, Request $request)
    {
        $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
    
        if (is_null($product)) {
            return $this->redirectToRoute('app_product');
        }
        
        $cart = $request->getSession()->get('cart', []);
    
        return $this->render('show.html.twig', [
            'product' => $product,
            'cart' => $cart,
        ]);
    }



    // ...
    
    #[Route('/product/{id}/add-to-cart', name: 'app_product_add_to_cart')]
    public function addToCart($id, EntityManagerInterface $entityManager, Request $request, Security $security)
    {
        // Step 1: Get the currently authenticated user
        $user = $security->getUser();
    
        // If the user is not logged in, redirect to the login page
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
    
        // Step 2: Retrieve the user's cart or create a new one if it doesn't exist
        $cart = $user->getPanier();
        if (!$cart) {
            $cart = new Panier();
            $cart->setUser($user);
            $entityManager->persist($cart);
        }
    
        // Step 3: Add the product to the user's cart
        $product = $entityManager->getRepository(Product::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Le produit n\'existe pas.');
        }
    
        $cart->addProduct($product);
    
        // Step 4: Save the changes to the database
        $entityManager->flush();
    
        // Add a flash message to indicate successful addition to the cart
        $this->addFlash('success', 'Le produit a été ajouté au panier avec succès.');
    
        // Redirect back to the product page (you can adjust this based on your requirements)
        return $this->redirectToRoute('app_product_show', ['id' => $id]);
    }
    

}