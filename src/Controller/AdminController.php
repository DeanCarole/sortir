<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use App\Form\Filter\AdminCities;
use App\Form\Filter\Filter;
use App\Form\FilterCitiesType;
use App\Form\FilterType;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard', name: 'dashboard_')]
class AdminController extends AbstractController
{
    #[Route('/city', name: 'city')]
    public function setCities(CityRepository $cityRepository, Request $request): Response
    {
        //Filtre pour les noms des villes
        $filterCitiesByName = new AdminCities();

        //Création formulaire du filtre
        $filterCities = $this->createForm(FilterCitiesType::class, $filterCitiesByName);
        $filterCities->handleRequest($request);

        //Récup et stockage de l'ensemble des villes
        $cities = $cityRepository->findCitiesByName($filterCitiesByName);

        return $this->render('admin/adminCities.html.twig', [
            'cities' => $cities,
            'filterCities' => $filterCities->createView()]);
    }

    #[Route('/campus', name: 'campus')]
    public function setCampuses(CampusRepository $campusRepository, Request $request): Response
    {

        //affiche tous les campus
        $campuses = $campusRepository->findAll();

        //je récupère le formulaire
      $campusForm = $this->createForm(CampusType::class);

     $campusForm->handleRequest($request);


        if ($campusForm->isSubmitted()) {

            //je récupère les données du formulaire
            $campus = $campusForm->getData();

            $campusRepository->save($campus, true);
            $this->addFlash('success', "Campus créé !");
        }

        return $this->render('admin/adminCampuses.html.twig', [
            'campuses' => $campuses, 'campusForm' => $campusForm->createView()]);
    }

//    #[Route('/campus/add', name: 'campusAdd')]
//    public function campusAdd(CampusRepository $campusRepository, Request $request): Response
//    {
//dd('coucou');
//ajout campus à la BDD
//        $saisie = $request->query->get('saisie');
//
//        $campus = new Campus();
//
//        $campusForm = $this->createForm(CampusType::class, $campus);
//        $campusForm->handleRequest($request);
//
//        $campus = $campusForm->getData();
//
//        $campusRepository->save($campus,true);
//        $this->addFlash('success',"Campus créé !");
//
//        return $this->redirectToRoute('dashboard_campus', [
//            'campusForm' => $campusForm->createView()]);
//    }


}



