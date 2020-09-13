<?php


namespace App\Controller;


use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;


class AdminController extends Controller
{
//***** Utilisateurs *****
    //Fonction qui permet de récupérer tous les utilisateurs
    /**
     * @Route("/users", name="users")
     */
    public function usersListAction(Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository("App:User");

        $listUsers = $repository->findAll();

        return $this->render('admin/userList.html.twig', ["listUsers"=>$listUsers]);
    }

    /**
     * @Route("/addtrainer/{id}", name="addtrainer")
     */
    public function addTrainer(Request $request, $id)
    {
        $em =$this->getDoctrine()->getManager();
        $user = $em->getRepository('App:User')->find($id);
        if (null === $user) {
            return $this->redirectToRoute("users");
        }
        $user ->addRole("ROLE_TRAINER");
        $em->flush();

        return $this->redirectToRoute('users');

    }

    /**
     * @Route("/deletetrainer/{id}", name="deletetrainer")
     */
    public function deleteTrainer(Request $request, $id)
    {
        $em =$this->getDoctrine()->getManager();
        $user = $em->getRepository('App:User')->find($id);
        if (null === $user) {
            return $this->redirectToRoute("users");
        }
        $user ->removeRole("ROLE_TRAINER");
        $em->flush();

        return $this->redirectToRoute('users');

    }
}