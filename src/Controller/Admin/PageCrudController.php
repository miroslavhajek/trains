<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Page;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Page::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setDefaultSort(['publishedAt' => 'DESC', 'createdAt' => 'DESC'])
            ->setPageTitle(Crud::PAGE_INDEX, 'Pages');
    }


    /**
     * @inheritdoc
     */
    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('id')
            ->setDisabled()
            ->hideOnForm();
        yield TextField::new('title');
        yield TextField::new('slug')
            ->setRequired($pageName !== Crud::PAGE_NEW);
        yield DateTimeField::new('publishedAt', 'Published at');
        yield TextEditorField::new('content')
            ->setFormTypeOption('sanitize_html', true)
            ->hideOnIndex()
            ->setColumns(12);
        yield DateTimeField::new('createdAt', 'Created at')
            ->setDisabled()
            ->hideOnForm();
    }
}
