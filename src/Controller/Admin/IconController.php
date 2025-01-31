<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Abstract\CrudController;
use App\Entity\Icon;
use App\Form\IconType;
use App\Repository\IconRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/icon', name: 'admin_icon_')]
final class IconController extends CrudController
{
    public function __construct(IconRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_icon_',
            'entity_class' => Icon::class,
            'form_class' => IconType::class,
            'main_title' => 'admin.title',
            'entity_name' => 'icon.name',
            'index_cols' => ['id', 'faClass', 'label'],
            'index_backlink' => [
                'text' => 'admin.backTo',
                'route' => 'default_index',
            ],
        ];
    }
}
