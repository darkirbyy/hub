<?php

declare(strict_types=1);

namespace App\Controller\Admin\Param;

use App\Controller\Abstract\CrudController;
use App\Entity\Param\Status;
use App\Form\Param\StatusType;
use App\Repository\Param\StatusRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/param/status', name: 'admin_param_status_')]
final class StatusController extends CrudController
{
    public function __construct(StatusRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_param_status_',
            'entity_class' => Status::class,
            'entity_key' => 'status',
            'form_class' => StatusType::class,
            'main_title' => 'admin.parameters',
            'index_cols' => [
                1 => ['getter' => 'id'],
                2 => ['getter' => 'number'],
                3 => ['getter' => 'icon', 'raw' => true],
                4 => ['getter' => 'label'],
                5 => ['getter' => 'url'],
            ],
            'index_backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
        ];
    }
}
