<?php

declare(strict_types=1);

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum PasswordStrengthEnum: string implements TranslatableInterface
{
    case VERY_WEAK = 'VERY_WEAK';
    case WEAK = 'WEAK';
    case MEDIUM = 'MEDIUM';
    case STRONG = 'STRONG';
    case VERY_STRONG = 'VERY_STRONG';

    // Implement the TranslatableInterface so that the label are automatically translated in the form
    public function trans(TranslatorInterface $trans, ?string $locale = null): string
    {
        return $trans->trans('enum.passwordStrength.' . $this->toTransKey(), locale: $locale);
    }

    // Convert to key used in the translations yaml
    public function toTransKey(): string
    {
        return match ($this) {
            self::VERY_WEAK => 'veryWeak',
            self::WEAK => 'weak',
            self::MEDIUM => 'medium',
            self::STRONG => 'strong',
            self::VERY_STRONG => 'veryStrong',
        };
    }

    // Convert to the min score variable used by the PasswordStrength Constraint
    public function toMinScore(): int
    {
        return match ($this) {
            self::VERY_WEAK => 0,
            self::WEAK => 1,
            self::MEDIUM => 2,
            self::STRONG => 3,
            self::VERY_STRONG => 4,
        };
    }
}
