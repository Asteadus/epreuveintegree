<?php

namespace App\Controller;

use App\Entity\Terrain;
use App\Form\TerrainType;
use App\Repository\TerrainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/terrain")
 */
class TerrainController extends AbstractController
{
    /**
     * @Route("/", name="terrain_index", methods={"GET"})
     */
    public function index(TerrainRepository $terrainRepository): Response
    {
        return $this->render('terrain/index.html.twig', [
            'terrains' => $terrainRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="terrain_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $terrain = new Terrain();
        $form = $this->createForm(TerrainType::class, $terrain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($terrain);
            $entityManager->flush();

            return $this->redirectToRoute('terrain_index');
        }

        return $this->render('terrain/new.html.twig', [
            'terrain' => $terrain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="terrain_show", methods={"GET"})
     */
    public function show(Terrain $terrain): Response
    {
        return $this->render('terrain/show.html.twig', [
            'terrain' => $terrain,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="terrain_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Terrain $terrain): Response
    {
        $form = $this->createForm(TerrainType::class, $terrain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('terrain_index');
        }

        return $this->render('terrain/edit.html.twig', [
            'terrain' => $terrain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="terrain_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Terrain $terrain): Response
    {
        if ($this->isCsrfTokenValid('delete'.$terrain->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($terrain);
            $entityManager->flush();
        }

        return $this->redirectToRoute('terrain_index');
    }
}
