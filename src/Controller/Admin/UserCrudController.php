<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserCrudController extends AbstractCrudController
{
    // on injecte le service pour hacher les mots de passe
    private UserPasswordHasherInterface $passwordHasher;

    // constructeur qui initialise le service
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    // on précise que cette classe gère des objets de type User
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // on configure les champs à afficher dans l'admin EasyAdmin
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('email'),
            TextField::new('password')->onlyOnForms(), 
            ChoiceField::new('roles') 
                ->setChoices([ // choix possibles pour les rôles
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices() 
                ->renderExpanded(), // affiche les rôles sous forme de cases à cocher
        ];
    }

    // on hache le mot de passe avant de sauvegarder l'entité
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $password = $entityInstance->getPassword(); // récupère le mot de passe
            if (!empty($password)) {
                $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $password); // on hache le mot de passe
                $entityInstance->setPassword($hashedPassword); // on remplace le mot de passe en clair par le haché
            }
        }
        parent::persistEntity($entityManager, $entityInstance); // on appelle la méthode parent pour persister l'entité
    }
}

