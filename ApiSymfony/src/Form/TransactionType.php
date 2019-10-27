<?php

namespace App\Form;

use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('montant')
            ->add('frais')
            ->add('cometat')
            ->add('comsystem')
            ->add('comenvoie')
            ->add('nomE')
            ->add('prenomE')
            ->add('telE')   
            ->add('nomEx')   
            ->add('prenomEx')   
            ->add('telephoneEx')   
            ->add('adresseEx')   
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
