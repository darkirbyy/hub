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

    protected function setConfigMain(): array
    {
        return [
            'route_prefix' => 'admin_hub_category_',
            'entity_class' => Category::class,
            'entity_key' => 'category',
            'form_class' => CategoryType::class,
            'main_title' => 'admin.configs',
        ];
    }

    protected function setConfigIndex(): array
    {
        return [
            'cols' => [
                // 1 => ['getter' => 'id'],
                2 => ['getter' => 'label'],
                3 => ['getter' => 'icon.faClass', 'label' => 'icon', 'filters' => 'fmt_fa_class|raw'],
            ],
            'backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
            'button' => [
                'show' => false,
            ],
            'sort' => 'label',
        ];
    }

    protected function setConfigShow(): array
    {
        return [
            'rows' => [
                0 => ['getter' => 'id'],
                1 => ['getter' => 'label'],
                2 => ['getter' => 'icon.faClass', 'label' => 'icon', 'filters' => 'fmt_fa_class|raw'],
            ],
        ];
    }
}
