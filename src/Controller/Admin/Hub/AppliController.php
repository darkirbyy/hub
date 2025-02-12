<?php

declare(strict_types=1);

namespace App\Controller\Admin\Hub;

use App\Controller\Abstract\CrudController;
use App\Entity\Hub\Appli;
use App\Form\Hub\AppliType;
use App\Repository\Hub\AppliRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/hub/appli', name: 'admin_hub_appli_')]
final class AppliController extends CrudController
{
    public function __construct(AppliRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_hub_appli_',
            'entity_class' => Appli::class,
            'entity_key' => 'appli',
            'form_class' => AppliType::class,
            'main_title' => 'admin.configs',
            'index_cols' => [
                // 1 => ['getter' => 'id'],
                1 => ['getter' => 'category'],
                2 => ['getter' => 'title'],
                3 => ['getter' => 'name', 'breakpoint' => 'md'],
                4 => ['getter' => 'public', 'filters' => 'fmt_bool|trans'],
                6 => ['getter' => 'imageMeta', 'filters' => 'fmt_image_meta', 'breakpoint' => 'md'],
            ],
            'index_backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
            'index_sort' => 'category.label',
        ];
    }
}
