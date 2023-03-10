<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/place', name: 'place_')]
class PlaceController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(Request $request, PlaceRepository $placeRepository): Response
    {
        $place = new Place();

        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);

        if ($placeForm->isSubmitted() && $placeForm->isValid()) {
            // récupérer les données du formulaire
            $place = $placeForm->getData();

            $placeRepository->save($place,true);
            $this->addFlash('success',"Lieu créé !");

        }

        return $this->render('place/add.html.twig', [
            'placeForm' => $placeForm->createView()]);
    }
}
