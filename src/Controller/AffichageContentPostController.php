<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        //$comments = $commentsRepository->findBy(['post' => $post], ['createdAt' => 'DESC']);

        if (!$post) {
            throw $this->createNotFoundException("Post non trouvé!");
        }

        $comments = $post->getComments()->toArray();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        /*
        if ($form->isSubmitted()) {
            dump('Formulaire soumis');
            if ($form->isValid()) {
                dump('Formulaire valide');
                // Processus d'ajout du commentaire
            } else {
                dump('Formulaire invalide');
            }
        }
*/
        /*
        if ($form->isSubmitted()) {
            dd('Formulaire soumis'); // Vérifie si le formulaire est bien soumis
        }

        if ($form->isSubmitted() && $form->isValid()) {
            dd('Formulaire valide'); // Vérifie si le formulaire est valide
        }*/


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

        return $this->render('posts/index.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'commentForm' => $form->createView(),
        ]);
    }
}