<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function home(): Response
    {
        // For the initial load, you can decide:
        // - to show all posts
        // - or to show no posts at all

        // Option A: Show NO posts by default
        $posts = [];
        $query = '';

        // Option B: Show ALL posts by default
        // $posts = $postRepository->findAll();
        // $query = '';

        return $this->render('home.html.twig', [
            'posts' => $posts,
            'query' => $query,
        ]);
    }
}