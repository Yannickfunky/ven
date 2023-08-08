<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController ; 
use Symfony\Component\HttpFoundation\Response ; 
use Symfony\Component\Routing\Annotation\Route ; 
use Doctrine\ORM\EntityManagerInterface ; 
use App\Entity\Panier ; 
use App\Entity\User ;  
use App\Repository\PanierRepository ;  
use App\Repository\UserRepository ;  
use Symfony\Component\Security\Core\Security ;  
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils ; 
use Symfony\Component\HttpFoundation\Request ;  
use App\Tax\CalculTva ; 

class PanierController extends AbstractController
{


#[Route('/panier', name: 'app_panier')]
public function index(PanierRepository $panierRepository, UserRepository $userRepository, Security $security, AuthenticationUtils $authenticationUtils): Response
{
    
 { if ($security !== null && $security->isGranted('IS_AUTHENTICATED_FULLY'))
    
    { $user = $security->getUser(); 
        $id = $user->getId(); 
    
    } else { 
        $error = $authenticationUtils->getLastAuthenticationError(); 
        $lastUsername = $authenticationUtils->getLastUsername(); 
        
        return $this->render('security/login.html.twig', [
             'error' => $error, 
             'lastUsername' => $lastUsername 
            ]); 
    
    } 
    
    $panier = $panierRepository->findOneBy(['user'=>$id]); 
    $product = $panier->getProduct() ; 
    $prix = 0 ; 
    
    foreach ($product as $p) { 
        $prix += $p-> getPrice(); 
    
    }

    $totalHT = $prix;
        $totalTVA = $prix * 0.2;
        $TTCPrice = $prix + $totalTVA ;
    
    return $this->render('panier/index.html.twig', [

    'products' => $product, 
    'TTCPrice' => $TTCPrice,
    'totalHT' => $totalHT,
    'totalTVA' => $totalTVA, 
]);

}
}

#[Route('/panier/{id}/remove', name: 'app_panier_remove')]
public function removeFromPanier($id, PanierRepository $panierRepository, Security $security, EntityManagerInterface $entityManager, Request $request): Response
    {

$user = $security->getUser(); 

$userId = $user->getId(); // Retrouver le panier de l'utilisateur 

$panier = $panierRepository->findOneBy(['user' => $userId]); // Si le panier n'existe pas, rediriger vers la page du panier similar_text

if (!$panier) { 
    return $this->redirectToRoute('app_panier'); 

} // Récupère les produits du panier 

$product = $panier->getProduct();

 // Recherche le produit à supprimer du panier 

$productToRemove = null; 

foreach ($product as $prod) { 
    if ($prod->getId() == $id) { 
        $productToRemove = $prod; 
        break; 
    } } 
    
    // Si le produit est trouvé, le retirer du panier et mettre à jour la base de données 
    
    if ($productToRemove) 
    
    { $panier->removeProduct($productToRemove);
        
        $entityManager->persist($panier); 
        
        $entityManager->flush();
        
        // Ajout d'un message flash pour indiquer la suppression réussie 
        
        $this->addFlash('success', 'Le produit a été supprimé du panier avec succès. '); 
    
    }


$TTCPrice = 0 ; 
$totalHT = 0 ; 
$totalTVA = 0 ; 

foreach ($product as $p) { 

    $totalHT += $p->getPrice(); 
    $totalTVA += $p->getPrice() * 0.2; 
    $TTCPrice += $p->getPrice() * 1.2; 

}

return $this->render('panier/index.html.twig', [
    'products' => $product,
    'TTCPrice' => $TTCPrice,
    'totalHT' => $totalHT,
    'totalTVA' => $totalTVA,
]);
    
    // Redirection vers la page panier return 
    
    return $this->redirectToRoute('app_panier'); 
} 
}
