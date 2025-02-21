<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Asset\Packages;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageResolver
{
    public function __construct(private UploaderHelper $helper, private Packages $assets)
    {
    }

    public function getImagePath(object $object, string $defaultImage, string $imageField = 'imageFile'): string
    {
        $imagePath = $this->helper->asset($object, $imageField);
        if ($imagePath) {
            return $imagePath;
        }

        $imagePath = $this->assets->getUrl('build/images/' . $defaultImage);

        return $imagePath;
    }
}
