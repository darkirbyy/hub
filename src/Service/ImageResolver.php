<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageResolver
{
    public function __construct(private UploaderHelper $helper, private Packages $assets, private RequestStack $requestStack)
    {
        $this->serverBaseUrl = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
    }

    public function getImagePath(object $object, string $defaultPath, string $imageField = 'imageFile'): string
    {
        $imagePath = $this->helper->asset($object, $imageField);
        if ($imagePath) {
            return $this->serverBaseUrl . $imagePath;
        }
        $fullUrl = $this->assets->getUrl('build/images/' . $defaultPath);

        return $fullUrl;
        // $urlParts = parse_url($fullUrl);
        // return $urlParts['path'];
    }
}
