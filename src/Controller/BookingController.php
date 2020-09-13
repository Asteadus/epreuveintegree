<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/booking", name="")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("/", name="booking_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findBookingByUserAnDate($this->getUser()),
        ]);
    }

    /**
     * @Route("/reservation", name="booking_new", methods={"GET","POST"})
     */
    public function new(Request $request, BookingRepository $bookingRepository): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        $date = new \DateTime('now');

        if ($form->isSubmitted() && $form->isValid()) {
            if ($booking->getBeginAt() < $booking->getEndAt()){
                if ($booking->getBeginAt() > $date) {
                    $diffdays=$booking->getEndAT()->diff($booking->getBeginAt());
                    $diffdays=$diffdays->format("%a");
                    $diffhours=$booking->getEndAT()->diff($booking->getBeginAt());
                    $diffhours=$diffhours->format("%h");


                    if(($this->getUser()->getRoles()[0]== 'ROLE_ADMIN' || $this->getUser()->getRoles()[0]== 'ROLE_TRAINER') || ($this->getUser()->getRoles()[0]== 'ROLE_USER' && ($diffdays<8 && $diffhours<4) )) {
                        if (!$bookingRepository->findRangeDate($booking->getBeginAt(), $booking->getEndAt(), $booking->getNumterrain())) {
                            // rajouter un AND dans le if pour vérifier si le terrain est déjà pris à cette heure
                            $entityManager = $this->getDoctrine()->getManager();
                            var_dump($booking);
                            $booking->setUser($this->getUser());
                            $booking->setBeginAt($booking->getBeginAt()->modify('-1 min'));
                            $booking->setendAt($booking->getEndAt()->modify('+1 min'));
                            $entityManager->persist($booking);
                            $entityManager->flush();
                            return $this->redirectToRoute('booking_new');
                            // LE MAIL JE DOIS L'ENVOYER ICI
                        } else {
                            $this->get('session')->getFlashBag()->add('error', 'Cette date est déjà prise !');
                        }
                    }else{
                        $this->get('session')->getFlashBag()->add('error', 'Lis les règles !');
                    }
                }
                else{
                    $this->get('session')->getFlashBag()->add('error', 'Cette date est périmée !');
                }
            }
            else{
                $this->get('session')->getFlashBag()->add('error', 'La date de début doit être avant celle de fin !');
            }
        }

        return $this->render('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="booking_show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="booking_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Booking $booking): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('booking_index');
        }

        return $this->render('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="booking_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('booking_index');
    }

    /**
     * @Route("/calendar/1", name="booking_calendar", methods={"GET"})
     */
    public function calendar(): Response
    {
        return $this->render('booking/calendar.html.twig');
    }

}
