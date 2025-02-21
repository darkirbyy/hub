<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultType extends AbstractType
{
    protected bool $htmlValidation;

    public function __construct(bool $htmlValidation)
    {
        $this->htmlValidation = $htmlValidation;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'validators',
            'attr' => [],
        ]);

        $resolver->setNormalizer('attr', function (Options $options, array $attr): array {
            if (!$this->htmlValidation) {
                $attr['novalidate'] = 'novalidate';
            }

            return $attr;
        });
    }
}
