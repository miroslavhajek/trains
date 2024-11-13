<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Device;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DeviceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Device::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPageTitle(Crud::PAGE_INDEX, 'Devices');
    }


    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }


    /**
     * @inheritdoc
     */
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('id')
            ->setDisabled();
        yield TextField::new('name');
        yield BooleanField::new('isOnline', 'Online')
            ->setDisabled();
        yield DateTimeField::new('lastOnlineAt', 'Last online at')
            ->setDisabled();
        yield DateTimeField::new('createdAt', 'Created at')
            ->setDisabled();
    }
}
