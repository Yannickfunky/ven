<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserFormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    
    $builder
    ->add('username', TextType::class, [
        'attr' => ['class' => 'form-control','placeholder' => 'Nom']
    ])

    ->add('email', EmailType::class, [
        'attr' => ['class' => 'form-control','placeholder' => 'Email']
    ])

    ->add('password', PasswordType::class, [
        'attr' => ['class' => 'form-control','placeholder' => 'Mot De passe']
    ]);
}

public function configureOptions(OptionsResolver $resolver): void
{
$resolver->setDefaults([
    'data_class' => User::class,
]);
}
}
?>