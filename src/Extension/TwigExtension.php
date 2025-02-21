<?php

declare(strict_types=1);

namespace App\Extension;

use App\Service\ImageResolver;
use Doctrine\ORM\PersistentCollection;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Vich\UploaderBundle\Entity\File as FileMeta;

class TwigExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(private ImageResolver $imageResolver, private TranslatorInterface $trans)
    {
    }

    public function getGlobals(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [new TwigFunction('deep_attribute', [$this, 'deepAttribute'])];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('apply_filters', [$this, 'applyFilters'], ['needs_environment' => true, 'is_safe' => ['html']]),
            new TwigFilter('ksort', [$this, 'sortByKeys']),
            new TwigFilter('get_image_path', [$this, 'getImagePath']),
            new TwigFilter('fmt_bool', [$this, 'fmtBool']),
            new TwigFilter('fmt_collec', [$this, 'fmtCollec']),
            new TwigFilter('fmt_password', [$this, 'fmtPassword']),
            new TwigFilter('fmt_fa_class', [$this, 'fmtFaClass']),
            new TwigFilter('fmt_image_meta', [$this, 'fmtImageMeta']),
            new TwigFilter('fmt_image_path', [$this, 'fmtImagePath']),
        ];
    }

    public function deepAttribute($object, $getter)
    {
        if ('self' == $getter) {
            return $object;
        }

        $attributes = explode('.', $getter);

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

    public function getImagePath(object $input, string $defaultImage, string $imageField = 'imageFile'): string
    {
        return $this->imageResolver->getImagePath($input, $defaultImage, $imageField);
    }

    public function fmtBool(bool $input): string
    {
        return $this->trans->trans($input ? 'enum.choices.yes' : 'enum.choices.no', [], 'messages');
    }

    public function fmtCollec(PersistentCollection $input, string $separator = ', '): string
    {
        return implode($separator, $input->toArray());
    }

    public function fmtPassword(string $input): string
    {
        return '***';
    }

    public function fmtFaClass(string $input, string $custom = ''): string
    {
        return '<span class="' . $input . ' ' . $custom . '"></span>';
    }

    public function fmtImageMeta(FileMeta $input): string
    {
        if (null !== $input->getName()) {
            return $input->getMimeType() . ' ; ' . \round($input->getSize() / 1024, 0) . 'Kio ; ' . $input->getWidth() . 'x' . $input->getHeight();
        } else {
            return '-';
        }
    }

    public function fmtImagePath(string $input, string $customClasses = 'img-fluid', string $altText = '')
    {
        return '<img src="' . $input . '" class="' . $customClasses . '" alt="' . $altText . '"/>';
    }
}
