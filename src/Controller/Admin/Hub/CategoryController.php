<?php

declare(strict_types=1);

namespace App\Controller\Admin\Hub;

use App\Controller\CrudController;
use App\Entity\Hub\Category;
use App\Form\Hub\CategoryType;
use App\Repository\Hub\CategoryRepository;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for managing categories in the admin panel.
 * Categories are used to organize all applis in different sections on the homepage.
 *
 * This class extends `CrudController`, automatically handling common CRUD operations.
 */
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
            'main_title' => 'admin.title',
        ];
    }

    protected function setConfigIndex(): array
    {
        return [
            'cols' => [
                // 0 => ['getter' => 'id'],
                1 => ['getter' => 'label'],
                2 => ['getter' => 'icon.faClass', 'label' => 'icon', 'filters' => 'fmt_fa_class|raw'],
                3 => ['getter' => 'number', 'breakpoint' => 'md'],
                4 => ['getter' => 'applis', 'filters' => 'fmt_collec|default("-")', 'breakpoint' => 'md'],
            ],
            'backlink' => [
                'text' => 'admin.link.backToMainPage',
                'route' => 'admin_index',
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
                2 => ['getter' => 'icon.faClass', 'label' => 'icon', 'filters' => 'fmt_fa_class|raw'],
                3 => ['getter' => 'number'],
                4 => ['getter' => 'applis', 'filters' => 'fmt_collec("<br>")|default("-")|raw'],
            ],
        ];
    }

    protected function setConfigNew(): array
    {
        return [
            'form_class' => CategoryType::class,
        ];
    }

    protected function setConfigEdit(): array
    {
        return [
            'form_class' => CategoryType::class,
        ];
    }
}
