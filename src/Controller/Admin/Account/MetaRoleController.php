<?php

declare(strict_types=1);

namespace App\Controller\Admin\Account;

use App\Controller\Abstract\CrudController;
use App\Entity\Account\MetaRole;
use App\Form\Account\MetaRoleType;
use App\Repository\Account\MetaRoleRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/account/metarole', name: 'admin_account_metarole_')]
final class MetaRoleController extends CrudController
{
    public function __construct(MetaRoleRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function setConfigMain(): array
    {
        return [
            'route_prefix' => 'admin_account_metarole_',
            'entity_class' => MetaRole::class,
            'entity_key' => 'metarole',
            'main_title' => 'admin.configs',
        ];
    }

    protected function setConfigIndex(): array
    {
        return [
            'cols' => [
                // 0 => ['getter' => 'id'],
                1 => ['getter' => 'key'],
                2 => ['getter' => 'roles', 'filters' => 'fmt_collec("<br>")|raw'],
                3 => ['getter' => 'description', 'breakpoint' => 'md'],
            ],
            'backlink' => [
                'text' => 'admin.backTo',
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
                2 => ['getter' => 'roles', 'filters' => 'fmt_collec("<br>")|raw'],
                3 => ['getter' => 'description'],
            ],
        ];
    }

    protected function setConfigNew(): array
    {
        return [
            'form_class' => MetaRoleType::class,
        ];
    }

    protected function setConfigEdit(): array
    {
        return [
            'form_class' => MetaRoleType::class,
        ];
    }
}
