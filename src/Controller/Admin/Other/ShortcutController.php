<?php

declare(strict_types=1);

namespace App\Controller\Admin\Other;

use App\Controller\Abstract\CrudController;
use App\Entity\Other\Shortcut;
use App\Form\Other\ShortcutType;
use App\Repository\Other\ShortcutRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/other/shortcut', name: 'admin_other_shortcut_')]
final class ShortcutController extends CrudController
{
    public function __construct(ShortcutRepository $repository)
    {
        parent::__construct($repository);
    }

    protected function getConfig(): array
    {
        return [
            'route_prefix' => 'admin_other_shortcut_',
            'entity_class' => Shortcut::class,
            'entity_key' => 'shortcut',
            'form_class' => ShortcutType::class,
            'main_title' => 'admin.others',
            'index_cols' => [
                1 => ['getter' => 'id'],
                2 => ['getter' => 'type'],
                3 => ['getter' => 'number'],
                4 => ['getter' => 'label'],
                5 => ['getter' => 'url', 'breakpoint' => 'md'],
            ],
            'index_backlink' => [
                'text' => 'admin.backTo',
                'route' => 'admin_index',
            ],
        ];
    }
}
