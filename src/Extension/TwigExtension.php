<?php

declare(strict_types=1);

namespace App\Extension;

use Symfony\Component\HttpFoundation\File\File;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Vich\UploaderBundle\Entity\File as FileMeta;

class TwigExtension extends AbstractExtension implements GlobalsInterface
{
    public function getGlobals(): array
    {
        return [];
    }

    public function getFunctions()
    {
        return [new TwigFunction('deep_attribute', [$this, 'deepAttribute'])];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('apply_filters', [$this, 'applyFilters'], ['needs_environment' => true, 'is_safe' => ['html']]),
            new TwigFilter('ksort', [$this, 'sortByKeys']),
            new TwigFilter('fmt_bool', [$this, 'fmtBool']),
            new TwigFilter('fmt_fa_class', [$this, 'fmtFaClass'], ['is_safe' => []]),
            new TwigFilter('fmt_image_file', [$this, 'fmtImageFile']),
            new TwigFilter('fmt_image_meta', [$this, 'fmtImageMeta']),
        ];
    }

    public function deepAttribute($object, $path)
    {
        $attributes = explode('.', $path);

        foreach ($attributes as $attribute) {
            $getter = 'get' . ucfirst($attribute);
            $isGetter = 'is' . ucfirst($attribute);
            $hasGetter = 'has' . ucfirst($attribute);

            if (method_exists($object, $getter)) {
                $object = $object->$getter();
            } elseif (method_exists($object, $isGetter)) {
                $object = $object->$isGetter();
            } elseif (method_exists($object, $hasGetter)) {
                $object = $object->$hasGetter();
            } else {
                return null;
            }
        }

        return $object;
    }

    public function applyFilters(Environment $env, $value, $filters)
    {
        $templateString = '{{ value|' . $filters . ' }}';
        $template = $env->createTemplate($templateString);

        return $template->render(['value' => $value]);
    }

    public function sortByKeys(array $input): array
    {
        ksort($input);

        return $input;
    }

    public function fmtBool(bool $input): string
    {
        return $input ? 'form.choice.yes' : 'form.choice.no';
    }

    public function fmtFaClass(string $input, string $custom = ''): string
    {
        return '<span class="' . $input . ' ' . $custom . '"></span>';
    }

    public function fmtImageFile(File $input, string $custom = ''): string
    {
        $path = explode('public', (string) $input);

        return '<img src="' . $path[1] . '" class="' . $custom . '"/>';
    }

    public function fmtImageMeta(FileMeta $input): string
    {
        return $input->getMimeType() . ' ; ' . \round($input->getSize() / 1024, 0) . 'Kio ; ' . $input->getWidth() . 'x' . $input->getHeight();
    }
}
