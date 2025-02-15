<?php

declare(strict_types=1);

namespace App\Controller\Admin\Account;

use App\Controller\Theme\CrudController;
use App\Entity\Account\Role;
use App\Form\Account\RoleType;
use App\Repository\Account\RoleRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/account/role', name: 'admin_account_role_')]
final class RoleController extends CrudController
{
    public function __construct(RoleRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function setConfigMain(): array
    {
        return [
            'route_prefix' => 'admin_account_role_',
            'entity_class' => Role::class,
            'entity_key' => 'role',
            'main_title' => 'admin.title',
        ];
    }

    protected function setConfigIndex(): array
    {
        return [
            'cols' => [
                // 0 => ['getter' => 'id'],
                1 => ['getter' => 'key'],
                2 => ['getter' => 'appli.name', 'label' => 'appli'],
                3 => ['getter' => 'description', 'breakpoint' => 'md'],
            ],
            'backlink' => [
                'text' => 'admin.link.backToMainPage',
                'route' => 'admin_index',
            ],
            'button' => [
                'show' => true,
            ],
            'repo_method' => 'findAndSort',
        ];
    }

    protected function setConfigShow(): array
    {
        return [
            'rows' => [
                0 => ['getter' => 'id'],
                1 => ['getter' => 'key'],
                2 => ['getter' => 'appli.name', 'label' => 'appli'],
                3 => ['getter' => 'description'],
            ],
        ];
    }

    protected function setConfigNew(): array
    {
        return [
            'form_class' => RoleType::class,
        ];
    }

    protected function setConfigEdit(): array
    {
        return [
            'form_class' => RoleType::class,
        ];
    }
}
