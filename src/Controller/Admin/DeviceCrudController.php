<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Admin\Dto\DeviceListItem;
use App\Entity\Device;
use App\Entity\DeviceLocation;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use function sprintf;

class DeviceCrudController extends AbstractCrudController
{
    public function __construct(private readonly AdminUrlGenerator $adminUrlGenerator)
    {
    }


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


    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters,
    ): QueryBuilder {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->select(
            sprintf(
                'new %s(
                    entity.id,
                    entity.name,
                    entity.createdAt,
                    (
                        SELECT MAX(l.createdAt)
                        FROM %s l
                        WHERE l.device = entity ORDER BY l.createdAt DESC
                    ),
                    \'xx\'
                )',
                DeviceListItem::class,
                DeviceLocation::class,
            ),
        );

        return $qb;
    }


    protected function getRedirectResponseAfterSave(
        AdminContext $context,
        string $action,
    ): RedirectResponse {
        $submitButtonName = $context->getRequest()->request->all('ea')['newForm']['btn'];

        if ($action === Crud::PAGE_EDIT && $submitButtonName === Action::SAVE_AND_RETURN) {
            $url = $this->adminUrlGenerator
                ->set(EA::ENTITY_ID, null)
                ->setController(self::class)
                ->setAction(Action::INDEX)
                ->generateUrl();

            return $this->redirect($url);
        }

        return parent::getRedirectResponseAfterSave($context, $action);
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
            ->hideOnForm()
            ->setDisabled();
        yield DateTimeField::new('lastOnlineAt', 'Last online at')
            ->hideOnForm()
            ->setDisabled();
        yield DateTimeField::new('createdAt', 'Created at')
            ->setDisabled();
    }
}
