<?php

declare(strict_types=1);

namespace App\Controller\Admin\Hub;

use App\Controller\Abstract\CrudController;
use App\Entity\Hub\Category;
use App\Form\Hub\CategoryType;
use App\Repository\Hub\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/hub/category', name: 'admin_hub_category_')]
final class CategoryController extends CrudController
{
    public function __construct(CategoryRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_hub_category_',
            'entity_class' => Category::class,
            'entity_key' => 'category',
            'form_class' => CategoryType::class,
            'main_title' => 'admin.configs',
            'index_cols' => [
                // 1 => ['getter' => 'id'],
                2 => ['getter' => 'label'],
                3 => ['getter' => 'icon'],
            ],
            'index_backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
        ];
    }
}
