<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
// permet d'afficher la page d'accueil
    /**
     * @Route("/homepage", name="homepage")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    public function header(User $user, Request $request){

        return $this->render('header.html.twig', ["user" => $user]);
    }

    /**
     * @Route("/profil/{id}",name="profil")
     */
    public function profil(User $user, Request $request, BookingRepository $bookingRepository, $id){


        return $this->render('default/profil.html.twig', ["user" => $user, 'bookings' => $bookingRepository->findBookingByUser($id)]);
    }



}
