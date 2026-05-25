<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Shortcut;
use App\Form\Admin\ShortcutType;
use App\Repository\ShortcutRepository;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for managing shortcuts in the admin panel.
 * Shortcuts are only displayed in the admin panel, and should linked to other admin pages of the server.
 *
 * This class extends `CrudController`, automatically handling common CRUD operations.
 */
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
                3 => ['getter' => 'number', 'breakpoint' => 'md'],
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
                4 => ['getter' => 'url'],
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
