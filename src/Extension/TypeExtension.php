<?php

declare(strict_types=1);

namespace App\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Extension that add a button_action option to all type field.
 */
class TypeExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('button_action', null);
        $resolver->setAllowedTypes('button_action', 'null|string');
        $resolver->setAllowedValues('button_action', [null, 'clear', 'reveal']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['button_action'] = $options['button_action'];
    }
}
