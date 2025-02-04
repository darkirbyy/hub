<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Abstract\CrudController;
use App\Entity\Appli;
use App\Form\AppliType;
use App\Repository\AppliRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/appli', name: 'admin_appli_')]
final class AppliController extends CrudController
{
    public function __construct(AppliRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_appli_',
            'entity_class' => Appli::class,
            'entity_key' => 'appli',
            'form_class' => AppliType::class,
            'main_title' => 'admin.title',
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
