<?php

declare(strict_types=1);

namespace App\Controller\Admin\Other;

use App\Controller\CrudController;
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

    protected function setConfigMain(): array
    {
        return [
            'route_prefix' => 'admin_other_shortcut_',
            'entity_class' => Shortcut::class,
            'entity_key' => 'shortcut',
            'main_title' => 'admin.title',
        ];
    }

    protected function setConfigIndex(): array
    {
        return [
            'cols' => [
                // 0 => ['getter' => 'id'],
                1 => ['getter' => 'label'],
                2 => ['getter' => 'type'],
                3 => ['getter' => 'number'],
                4 => ['getter' => 'url', 'breakpoint' => 'md'],
            ],
            'backlink' => [
                'text' => 'admin.link.backToMainPage',
                'route' => 'admin_index',
            ],
            'button' => [
                'show' => true,
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
                2 => ['getter' => 'type'],
                3 => ['getter' => 'number'],
                4 => ['getter' => 'url', 'breakpoint' => 'md'],
            ],
        ];
    }

    protected function setConfigNew(): array
    {
        return [
            'form_class' => ShortcutType::class,
        ];
    }

    protected function setConfigEdit(): array
    {
        return [
            'form_class' => ShortcutType::class,
        ];
    }
}
