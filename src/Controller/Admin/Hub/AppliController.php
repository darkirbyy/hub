<?php

declare(strict_types=1);

namespace App\Controller\Admin\Hub;

use App\Controller\CrudController;
use App\Entity\Hub\Appli;
use App\Form\Hub\AppliType;
use App\Repository\Hub\AppliRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for managing applications in the admin panel.
 * Applications are displayed on the homepage, and are associated with roles (see {@see \App\Controller\Admin\Account\RoleController}).
 *
 * This class extends `CrudController`, automatically handling common CRUD operations.
 */
#[Route('/admin/hub/appli', name: 'admin_hub_appli_')]
final class AppliController extends CrudController
{
    public function __construct(AppliRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function setConfigMain(): array
    {
        return [
            'route_prefix' => 'admin_hub_appli_',
            'entity_class' => Appli::class,
            'entity_key' => 'appli',
            'main_title' => 'admin.title',
        ];
    }

    protected function setConfigIndex(): array
    {
        return [
            'cols' => [
                // 0 => ['getter' => 'id'],
                1 => ['getter' => 'title'],
                2 => ['getter' => 'category'],
                3 => ['getter' => 'number'],
                4 => ['getter' => 'public', 'filters' => 'fmt_bool'],
                5 => ['getter' => 'name', 'breakpoint' => 'md'],
                6 => ['getter' => 'imageMeta', 'filters' => 'fmt_image_meta', 'breakpoint' => 'md'],
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
                1 => ['getter' => 'title'],
                2 => ['getter' => 'category'],
                3 => ['getter' => 'number'],
                4 => ['getter' => 'public', 'filters' => 'fmt_bool'],
                5 => ['getter' => 'name'],
                6 => ['getter' => 'path'],
                7 => ['getter' => 'description'],
                8 => ['getter' => 'linkText'],
                9 => ['getter' => 'self', 'label' => 'imageFile', 'filters' => 'get_image_path("default-appli.png")|fmt_image_path("bg-primary-subtle")|raw'],
                10 => ['getter' => 'imageMeta', 'filters' => 'fmt_image_meta'],
                11 => ['getter' => 'externalLinks', 'filters' => 'fmt_collec'],
            ],
        ];
    }

    protected function setConfigNew(): array
    {
        return [
            'form_class' => AppliType::class,
        ];
    }

    protected function setConfigEdit(): array
    {
        return [
            'form_class' => AppliType::class,
        ];
    }
}
