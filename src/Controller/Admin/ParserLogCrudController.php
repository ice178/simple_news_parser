<?php

namespace App\Controller\Admin;

use App\Entity\ParserLog;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ParserLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ParserLog::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->remove('index', 'edit');
        $actions->remove('index', 'new');
        $actions->remove('index', 'delete');

        return $actions;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
