<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Utils\TextFormatter;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AffichageContentPostController extends AbstractController
{
    #[Route('/actus/post/{id}', name: 'app_actus_post', methods: ['GET', 'POST'])]
    public function showPost(
        int $id,
        Post $post,
        PostRepository $postRepository,
        CommentRepository $commentsRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException("Post non trouvé!");
        }

        $comments = $post->getComments()->toArray();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $comment->setPost($post);
            $comment->setUser($this->getUser());
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setUpdatedAt(new \DateTimeImmutable());
            $comment->setIsReported(false);

            $entityManager->persist($comment);
            $entityManager->flush();

            $post->getComments()->add($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès.');

            return $this->redirectToRoute('app_actus_post', ['id' => $post->getId()]);
        }

        $formattedContent = TextFormatter::formatTextToHtml($post->getContent());

        return $this->render('posts/index.html.twig', [
            'post' => $post,
            'formattedContent' => $formattedContent,
            'comments' => $comments,
            'commentForm' => $form->createView(),
        ]);
    }

  
    #[Route('/comment/report/{id}', name: 'app_comment_report')]
    public function reportComment(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        $comment = $entityManager->getRepository(Comment::class)->find($id);
    
        if (!$comment) {
            throw $this->createNotFoundException('Comment not found.');
        }
    
        $postId = $comment->getPost()->getId();
    
        if (!$comment->isReported()) {
            $comment->setIsReported(true);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire signalé avec succès.');
        } else {
            $this->addFlash('warning', "Ce commentaire a déjà été signalé");
        }
    
        return $this->redirectToRoute('app_actus_post', ['id' => $postId]);
    }
    
}