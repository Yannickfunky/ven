<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeController extends AbstractController
{


    #[Route('/paiement',name: 'payment')]
    public function showPaymentForm()
    {
        $stripePublicKey = $this->getParameter('stripe_public_key');

        return $this->render('payment.html.twig',[
            'stripe_public_key' => $stripePublicKey

        ]);
    }

    #[Route("/create_payment", name: "create_payment", methods: "POST")]
    public function createPayment (Request $request)
    {
        $secretKey = $this->getParameter('stripe_secret_key');
        Stripe::setApiKey($secretKey);

        $paymentToken = $request->request->get('payment_token');

        try {

            $paymentIntent = PaymentIntent::create([
                'amount' => 20000000, //Montant en centimes (ici 20.00€)
                'currency' => 'eur',
                'payment_method' => $paymentToken,
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);

            return new Response('Paiement créé avec succès !');
        } catch (\Stripe\Exception\CardException $e) {
            return new Response('Erreur lors du paiement : '.$e->getMessage());
        } catch (\Stripe\Exception\StripeException $e) {
            return new Response('Erreur Stripe : '.$e->getMessage());
          }
    }
}