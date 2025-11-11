<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Abstract FormType that use an environment variable to enable or not the HTML validation.
 * Any other FormType CAN derived from it, but cannot override this parameter.
 */
abstract class DefaultType extends AbstractType
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
            'csrf_token_id' => 'hub/' . $this->getBlockPrefix(),
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
