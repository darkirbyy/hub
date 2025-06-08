<?php

declare(strict_types=1);

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AppliStatusEnum: string implements TranslatableInterface
{
    case PUBLIC = 'public';
    case USERONLY = 'user_only';
    case PRIVATE = 'private';

    // Implement the TranslatableInterface so that the label are automatically translated in the form
    public function trans(TranslatorInterface $trans, ?string $locale = null): string
    {
        return match ($this) {
            self::PUBLIC => $trans->trans('enum.appliStatus.public', locale: $locale),
            self::USERONLY => $trans->trans('enum.appliStatus.userOnly', locale: $locale),
            self::PRIVATE => $trans->trans('enum.appliStatus.private', locale: $locale),
        };
    }
}
