<?php

declare(strict_types=1);

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum LoginTypeEnum: string implements TranslatableInterface
{
    case NORMAL = 'NORMAL';
    case FORCED = 'FORCED';

    // Implement the TranslatableInterface so that the label are automatically translated in the form
    public function trans(TranslatorInterface $trans, ?string $locale = null): string
    {
        return $trans->trans('enum.loginType.' . $this->toTransKey(), locale: $locale);
    }

    // Convert to key used in the translations yaml
    public function toTransKey(): string
    {
        return match ($this) {
            self::NORMAL => 'normal',
            self::FORCED => 'forced',
        };
    }
}
