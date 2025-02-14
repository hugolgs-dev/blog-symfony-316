<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function home(PostRepository $postRepository): Response
    {
        $latestPosts = $postRepository->findBy([], ['createdAt' => 'DESC'], 3); // Récupère les 3 derniers posts

        return $this->render('home.html.twig', [
            'latestPosts' => $latestPosts,
        ]);
    }
}