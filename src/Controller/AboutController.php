<?php  

// src/Controller/AboutController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/a-propos-de-nous', name: 'about_us')]
    public function aboutUs(): Response
    {
        return $this->render('about/a-propos-de-nous.html.twig');

    }
}