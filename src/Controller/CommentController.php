<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CommentController extends AbstractController
{
    #[Route('/comment/report/{id}', name: 'app_comment_report', methods: ['POST'])]
    public function report(Comment $comment, EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $comment->setIsReported(false);
        $entityManager->flush();

        $this->addFlash('success', 'Le commentaire a été signalé.');

        return $this->redirect($request->headers->get('referer', '/'));
    }
}

