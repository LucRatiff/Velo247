<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CircuitController extends AbstractController
{
    #[Route('/circuit', name: 'circuits')]
    public function index(): Response
    {
        return $this->render('comming_soon.html.twig', []);
    }
}
