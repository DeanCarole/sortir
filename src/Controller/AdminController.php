<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/dashboard', name: 'dashboard_')]
class AdminController extends AbstractController
{
    #[Route('/city', name: 'city')]
    public function setCities(): Response
    {
        return $this->render('admin/adminCities.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/campus', name: 'campus')]
    public function setCampuses(): Response
    {
        return $this->render('admin/adminCampuses.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}



