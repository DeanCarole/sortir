<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Form\CityType;
use App\Form\Filter\AdminCampuses;
use App\Form\FilterCampusesType;
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

        //Création du formulaire d'ajout de ville
        $citiesForm = $this->createForm(CityType::class);
        $citiesForm->handleRequest($request);

        //Récup et stockage de l'ensemble des villes
        $cities = $cityRepository->findCitiesByName($filterCitiesByName);

        if($citiesForm->isSubmitted()){
            $city = $citiesForm->getData();
            $cityRepository->save($city, true);
            $this->addFlash('success', "Ville créée !");
        }

        return $this->render('admin/adminCities.html.twig', [
            'cities' => $cities,
            'cityForm' => $citiesForm->createView(),
            'filterCities' => $filterCities->createView()]);
    }

    #[Route('/campus', name: 'campus')]
    public function setCampuses(CampusRepository $campusRepository, Request $request): Response
    {
        //Filtre pour les noms des campus
        $filterCampusesByName = new AdminCampuses();

        //Création formulaire du filtre
        $filterCampuses = $this->createForm(FilterCampusesType::class, $filterCampusesByName);
        $filterCampuses->handleRequest($request);

        //Création du formulaire d'ajout de campus
        $campusForm = $this->createForm(CampusType::class);
        $campusForm->handleRequest($request);

        //Récup et stockage de l'ensemble des campus
        $campuses = $campusRepository->findCampusesByName($filterCampusesByName);

        if ($campusForm->isSubmitted()) {
            //je récupère les données du formulaire
            $campus = $campusForm->getData();
            $campusRepository->save($campus, true);
            $this->addFlash('success', "Campus créé !");
        }

        return $this->render('admin/adminCampuses.html.twig', [
            'campuses' => $campuses,
            'campusForm' => $campusForm->createView(),
            'filterCampuses' => $filterCampuses->createView()]);
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



