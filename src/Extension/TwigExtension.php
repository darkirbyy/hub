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

/**
 * Extension that provides additional functions and filters for use in templates.
 */
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
            new TwigFilter('fmt_enum', [$this, 'fmtEnum']),
            new TwigFilter('fmt_collec', [$this, 'fmtCollec']),
            new TwigFilter('fmt_password', [$this, 'fmtPassword']),
            new TwigFilter('fmt_fa_class', [$this, 'fmtFaClass']),
            new TwigFilter('fmt_image_meta', [$this, 'fmtImageMeta']),
            new TwigFilter('fmt_image_path', [$this, 'fmtImagePath']),
        ];
    }

    /**
     * Recursively retrieves a nested property using dot notation.
     *
     * @param object $object this object to traverse
     * @param string $getter this dot-notated path to the attribute
     *
     * @return mixed|null this attribute value, or null if not found
     */
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

    /**
     * Dynamically applies Twig filters to a value.
     *
     * @param Environment $env     the Twig environment
     * @param mixed       $value   the value to filter
     * @param string      $filters a string representing the filters to apply (e.g., `"upper|escape"`)
     *
     * @return string the filtered value
     */
    public function applyFilters(Environment $env, $value, $filters)
    {
        $templateString = '{{ value|' . $filters . ' }}';
        $template = $env->createTemplate($templateString);

        return $template->render(['value' => $value]);
    }

    /**
     * Sorts an associative array by its keys.
     */
    public function sortByKeys(array $input): array
    {
        ksort($input);

        return $input;
    }

    /**
     * Retrieves the image path of an entity (see {@see ImageResolver::getImagePath}).
     */
    public function getImagePath(object $input, string $defaultImage, string $imageField = 'imageFile'): string
    {
        return $this->imageResolver->getImagePath($input, $defaultImage, $imageField);
    }

    /**
     * Formats a enum using the translator interface.
     */
    public function fmtEnum(mixed $input): string
    {
        return $input->trans($this->trans);
    }

    /**
     * Formats a boolean value as a translated "Yes" or "No".
     */
    public function fmtBool(bool $input): string
    {
        return $this->trans->trans($input ? 'enum.choice.yes' : 'enum.choice.no', [], 'messages');
    }

    /**
     * Format a collection by using the __toString value on each element, then combining them with a comma.
     */
    public function fmtCollec(PersistentCollection $input, string $separator = ', ', string $getter = '__toString'): string
    {
        return implode($separator, array_map(fn ($object) => $object->$getter(), $input->toArray()));
    }

    /**
     * Masks a password by replacing it with `"***"`.
     */
    public function fmtPassword(string $input): string
    {
        return '***';
    }

    /**
     * Generates an HTML `<span>` element for a FontAwesome icon, adding the $custom classes.
     */
    public function fmtFaClass(string $input, string $custom = ''): string
    {
        return '<span class="' . $input . ' ' . $custom . '"></span>';
    }

    /**
     * Formats metadata of an uploaded image file.
     */
    public function fmtImageMeta(FileMeta $input): string
    {
        return $input->getMimeType() . ' ; ' . \round($input->getSize() / 1024, 0) . 'Kio ; ' . $input->getWidth() . 'x' . $input->getHeight();
    }

    /**
     * Generates an HTML `<img>` tag with the given path, classes (default `app-img-fluid`) and alt text (default to empty).
     */
    public function fmtImagePath(string $input, string $customClasses = 'app-img-fluid', string $altText = '')
    {
        return '<img src="' . $input . '" class="' . $customClasses . '" alt="' . $altText . '"/>';
    }
}
