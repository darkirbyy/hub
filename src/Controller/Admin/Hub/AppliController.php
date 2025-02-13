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

    protected function setConfigMain(): array
    {
        return [
            'route_prefix' => 'admin_hub_appli_',
            'entity_class' => Appli::class,
            'entity_key' => 'appli',
            'form_class' => AppliType::class,
            'main_title' => 'admin.configs',
        ];
    }

    protected function setConfigIndex(): array
    {
        return [
            'cols' => [
                // 1 => ['getter' => 'id'],
                1 => ['getter' => 'category'],
                2 => ['getter' => 'title'],
                3 => ['getter' => 'name', 'breakpoint' => 'md'],
                4 => ['getter' => 'public', 'filters' => 'fmt_bool|trans'],
                6 => ['getter' => 'imageMeta', 'filters' => 'fmt_image_meta', 'breakpoint' => 'md'],
            ],
            'backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
            'button' => [
                'show' => true,
            ],
            'sort' => 'category.label',
        ];
    }

    protected function setConfigShow(): array
    {
        return [
            'rows' => [
                0 => ['getter' => 'id'],
                1 => ['getter' => 'category'],
                2 => ['getter' => 'public', 'filters' => 'fmt_bool|trans'],
                3 => ['getter' => 'title'],
                4 => ['getter' => 'name'],
                5 => ['getter' => 'path'],
                6 => ['getter' => 'description'],
                7 => ['getter' => 'linkText'],
                8 => ['getter' => 'imageFile', 'filters' => 'fmt_image_file("img-fluid")|raw'],
                9 => ['getter' => 'imageMeta', 'filters' => 'fmt_image_meta'],
            ],
        ];
    }
}
