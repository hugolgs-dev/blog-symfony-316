<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AffichageContentPostController extends AbstractController
{
    #[Route('/actus/post/{id}', name: 'app_actus_post', methods: ['GET'])]
    public function index(int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException("Post non trouvé!");
        }

        return $this->render('posts/index.html.twig', [
            'post' => $post
        ]);
    }
}