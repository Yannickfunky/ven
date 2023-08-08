<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserFormulaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Form\FormTypeInterface;


class CreateUtilisateurController extends AbstractController
{
#[Route('/create/utilisateur', name: 'app_create_utilisateur')]
public function index(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
{
    $user = new User();
    $form = $this->createForm(UserFormulaireType::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        dump($form->getData());
        // Effectuez ici les opérations nécessaires pour enregistrer l'utilisateur dans la base de données, par exemple :
        $user->setPassword($passwordHasher->hashPassword ($user,$form->get('password')->getData()));
        $entityManager->persist($user);
        $entityManager->flush();

        // Redirigez vers une page de confirmation ou effectuez d'autres actions nécessaires
        return $this->redirectToRoute('main');
    }

    return $this->render('create_utilisateur/index.html.twig', [
        'form' => $form->createView(),
    ]);
}
}






