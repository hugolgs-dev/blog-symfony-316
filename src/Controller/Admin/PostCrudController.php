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
        $query = $request->query->get('q', '');
        $posts = $query ? $postRepository->searchPosts($query) : [];
    
        return $this->render('home.html.twig', [
            'posts' => $posts,
            'query' => $query
        ]);
    }
    


}
