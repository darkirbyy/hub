<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Asset\Packages;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Service to generate a valid path of a uploaded image, handling the empty case with a placeholder from the assets.
 */
class ImageResolver
{
    public function __construct(private UploaderHelper $helper, private Packages $assets)
    {
    }

    /**
     * Generates the path of an entity's image containing a VichUpload field.
     * Returns the path of a default placeholder image if no image is available.
     *
     * @param object $object       the entity containing at least one VichUpload image
     * @param string $defaultImage the placeholder image path, relative to the assets/images directory
     * @param string $imageField   the field of the entity tagged as VichUpload (default: 'imageFile')
     *
     * @return string the relative path of the image from the server root
     */
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
