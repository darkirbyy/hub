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

    protected function setConfigMain(): array
    {
        return [
            'route_prefix' => 'admin_hub_icon_',
            'entity_class' => Icon::class,
            'entity_key' => 'icon',
            'form_class' => IconType::class,
            'main_title' => 'admin.configs',
        ];
    }

    protected function setConfigIndex(): array
    {
        return [
            'cols' => [
                // 1 => ['getter' => 'id'],
                2 => ['getter' => 'label'],
                3 => ['getter' => 'faClass'],
                4 => ['getter' => 'faClass', 'label' => 'preview', 'filters' => 'fmt_fa_class|raw'],
            ],
            'backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
            'button' => [
                'show' => false,
            ],
            'repo_method' => 'findAndSort',
        ];
    }

    protected function setConfigShow(): array
    {
        return [
            'rows' => [
                0 => ['getter' => 'id'],
                1 => ['getter' => 'label'],
                2 => ['getter' => 'faClass'],
                3 => ['getter' => 'faClass', 'label' => 'preview', 'filters' => 'fmt_fa_class|raw'],
            ],
        ];
    }
}
