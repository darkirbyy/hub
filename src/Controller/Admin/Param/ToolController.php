<?php

declare(strict_types=1);

namespace App\Controller\Admin\Param;

use App\Controller\Abstract\CrudController;
use App\Entity\Param\Tool;
use App\Form\Param\ToolType;
use App\Repository\Param\ToolRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/param/tool', name: 'admin_param_tool_')]
final class ToolController extends CrudController
{
    public function __construct(ToolRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_param_tool_',
            'entity_class' => Tool::class,
            'entity_key' => 'tool',
            'form_class' => ToolType::class,
            'main_title' => 'admin.parameters',
            'index_cols' => [
                1 => ['getter' => 'id'],
                2 => ['getter' => 'type'],
                3 => ['getter' => 'number'],
                4 => ['getter' => 'icon', 'raw' => true, 'breakpoint' => 'md'],
                5 => ['getter' => 'label'],
                6 => ['getter' => 'url', 'breakpoint' => 'md'],
            ],
            'index_backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
        ];
    }
}
