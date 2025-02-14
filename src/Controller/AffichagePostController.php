<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;

final class AffichagePostController extends AbstractController{
    #[Route('/actus', name: 'app_actus')]
    public function index(PostRepository $postRepository): Response
    {
        $latestPosts = $postRepository->findBy([], ['createdAt' => 'DESC'], 3); // Récupère les 3 derniers posts
        $posts = $postRepository->findAll(); // Récupère tous les posts

        return $this->render('actus.html.twig', [
            'posts' => $posts,
            'latestPosts' => $latestPosts,
        ]);
    }
}
