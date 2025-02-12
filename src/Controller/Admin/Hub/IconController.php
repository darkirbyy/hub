<?php

declare(strict_types=1);

namespace App\Controller\Admin\Hub;

use App\Controller\Abstract\CrudController;
use App\Entity\Hub\Icon;
use App\Form\Hub\IconType;
use App\Repository\Hub\IconRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/hub/icon', name: 'admin_hub_icon_')]
final class IconController extends CrudController
{
    public function __construct(IconRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_hub_icon_',
            'entity_class' => Icon::class,
            'entity_key' => 'icon',
            'form_class' => IconType::class,
            'main_title' => 'admin.configs',
            'index_cols' => [
                // 1 => ['getter' => 'id'],
                2 => ['getter' => 'label'],
                3 => ['getter' => 'faClass'],
                4 => ['getter' => 'faClass', 'label' => 'preview', 'filters' => 'fmt_fa_class|raw'],
            ],
            'index_backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
            'index_sort' => 'label',
        ];
    }
}
