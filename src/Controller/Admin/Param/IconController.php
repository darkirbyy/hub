<?php

declare(strict_types=1);

namespace App\Controller\Admin\Param;

use App\Controller\Abstract\CrudController;
use App\Entity\Param\Icon;
use App\Form\Param\IconType;
use App\Repository\Param\IconRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/param/icon', name: 'admin_param_icon_')]
final class IconController extends CrudController
{
    public function __construct(IconRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_param_icon_',
            'entity_class' => Icon::class,
            'entity_key' => 'icon',
            'form_class' => IconType::class,
            'main_title' => 'admin.parameters',
            'index_cols' => [
                1 => ['getter' => 'id'],
                2 => ['getter' => 'label'],
                3 => ['getter' => 'faClass'],
                4 => ['getter' => 'preview', 'raw' => true],
            ],
            'index_backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
        ];
    }
}
