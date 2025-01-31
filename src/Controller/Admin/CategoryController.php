<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Abstract\CrudController;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category', name: 'admin_category_')]
final class CategoryController extends CrudController
{
    public function __construct(CategoryRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_category_',
            'entity_class' => Category::class,
            'entity_key' => 'category',
            'form_class' => CategoryType::class,
            'main_title' => 'admin.title',
            'index_cols' => ['id', 'label', 'icon'],
            'index_backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
        ];
    }
}
