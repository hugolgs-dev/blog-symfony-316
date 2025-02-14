<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Repository\PostRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    #[Route('/', name: 'home')]
    #[Route('/search', name: 'post_search', methods: ['GET'])]
    public function home(Request $request, PostRepository $postRepository): Response
    {
        // Récupération du terme de recherche
        $query = $request->query->get('q');

        if ($query) {
            // Si une recherche est effectuée, on récupère uniquement les résultats correspondants
            $latestPosts = $postRepository->createQueryBuilder('p')
                ->where('p.title LIKE :query OR p.content LIKE :query')
                ->setParameter('query', "%$query%")
                ->getQuery()
                ->getResult();
        } else {
            // Sinon, on affiche les derniers articles
            $latestPosts = $postRepository->findBy([], ['createdAt' => 'DESC'], 6);
        }

        return $this->render('home.html.twig', [
            'query' => $query,
            'latestPosts' => $latestPosts, // Utilisé pour afficher soit la recherche, soit les derniers articles
        ]);
    }



}
