<?php

declare(strict_types=1);

namespace App\Extension;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension implements GlobalsInterface
{
    public function getGlobals(): array
    {
        return [];
    }

    public function getFilters(): array
    {
        return [new TwigFilter('ksort', [$this, 'sortByKeys'])];
    }

    public function sortByKeys(array $input): array
    {
        ksort($input);

        return $input;
    }
}
