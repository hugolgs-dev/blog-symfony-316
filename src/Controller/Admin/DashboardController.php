<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Controller\Admin\PostCrudController;
use App\Controller\Admin\CommentCrudController;
use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{
    private AuthorizationCheckerInterface $authChecker;

    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //vérifie si l'utilisateur a le rôle 'ROLE_ADMIN'
        if (!$this->authChecker->isGranted('ROLE_ADMIN')) {
            //ajoute un message flash indiquant que l'utilisateur n'a pas accès
            $this->addFlash('error', 'Vous n\'êtes pas administrateur, vous n\'avez pas le droit d\'accéder à cette page.');

            // si utilisateur pas connecté => redirige vers page de connexion
            return $this->redirectToRoute('app_login');

        }

        //si l'utilisateur est un administrateur, affiche le tableau de bord
        return $this->render('/admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Blog Symfony 316');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Article', 'fa fa-posts', Post::class);
        yield MenuItem::linkToCrud('Commentaires', 'fa fa-comments', Comment::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);

    }
}


