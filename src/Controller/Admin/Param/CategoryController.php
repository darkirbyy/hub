<?php

declare(strict_types=1);

namespace App\Controller\Admin\Param;

use App\Controller\Abstract\CrudController;
use App\Entity\Param\Category;
use App\Form\Param\CategoryType;
use App\Repository\Param\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/param/category', name: 'admin_param_category_')]
final class CategoryController extends CrudController
{
    public function __construct(CategoryRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_param_category_',
            'entity_class' => Category::class,
            'entity_key' => 'category',
            'form_class' => CategoryType::class,
            'main_title' => 'admin.parameters.title',
            'index_cols' => [
                1 => ['getter' => 'id'],
                2 => ['getter' => 'label'],
                3 => ['getter' => 'icon', 'raw' => true],
            ],
            'index_backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
        ];
    }
}
