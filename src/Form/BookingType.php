<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Faker\Provider\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Mime\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class BookingType extends AbstractType
{

    private $security;



    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('beginAt', DateTimeType::class,[
                'data' => new \DateTime("now"),
                'minutes' => array(
                    0 => '00',
                    30 => '30',
                ),
                'hours' =>range(8,22),
                'widget' => 'choice',
                "label" => 'Début de la réservation',

            ])
            ->add('endAt', DateTimeType::class,[
                'widget' => 'choice',
                'data' => new \DateTime("now"),
                'minutes' => array(
                    0 => '00',
                    30 => '30',
                ),
                'hours' =>range(8,22),
                "label" => 'Fin de la réservation',
            ])
            ->add('title',null,[
                'label' =>'Nom de la réservation',
            ])
            ->add('numterrain',null,[
                'label' =>'Numéro terrain ',
            ])

            ->add('save', SubmitType::class,[
                "label" => 'Enregistrer la réservation',
                "attr" =>['class' =>'btn btn-primary btn-lg'],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
