<?php

declare(strict_types=1);

namespace App\Controller\Admin\Param;

use App\Controller\Abstract\CrudController;
use App\Entity\Param\Appli;
use App\Form\Param\AppliType;
use App\Repository\Param\AppliRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/param/appli', name: 'admin_param_appli_')]
final class AppliController extends CrudController
{
    public function __construct(AppliRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_param_appli_',
            'entity_class' => Appli::class,
            'entity_key' => 'appli',
            'form_class' => AppliType::class,
            'main_title' => 'admin.parameters',
            'index_cols' => [
                1 => ['getter' => 'id'],
                2 => ['getter' => 'name'],
                3 => ['getter' => 'path', 'breakpoint' => 'md'],
                4 => ['getter' => 'category'],
                5 => ['getter' => 'imageInfos', 'breakpoint' => 'md'],
            ],
            'index_backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
        ];
    }
}
