<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    TextField, TextEditorField, AssociationField, BooleanField, DateTimeField, IdField, TextareaField
};

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextareaField::new('content', 'Commentaire'),
            DateTimeField::new('createdAt', 'Créé le')->setFormTypeOption('disabled', 'disabled'),
            DateTimeField::new('updatedAt', 'Mis à jour le')->hideOnIndex(),
            BooleanField::new('isReported', 'Signalé ?'),
            AssociationField::new('user', 'Utilisateur'),
            AssociationField::new('post', 'Article'),
        ];
    }

}
