<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RepairController extends AbstractController
{
    #[Route('/repair', name: 'repair')]
    public function index(): Response
    {
        return $this->render('comming_soon.html.twig', []);
    }
}
