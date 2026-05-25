<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\ExternalLink;
use App\Entity\Icon;
use App\Form\DefaultType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExternalLinkType extends DefaultType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('icon', EntityType::class, [
                'required' => true,
                'class' => Icon::class,
                'choice_label' => 'label',
                'label' => 'externalLink.label.icon',
                'attr' => [
                    'data-collec-field-target' => 'focus',
                    'placeholder' => 'externalLink.label.icon',
                ],
            ])
            ->add('text', TextType::class, [
                'required' => false,
                'label' => 'externalLink.label.text',
                'attr' => [
                    'placeholder' => 'externalLink.label.text',
                ],
            ])
            ->add('url', UrlType::class, [
                'required' => true,
                'default_protocol' => 'http',
                'label' => 'externalLink.label.url',
                'row_attr' => [
                    'class' => 'flex-grow-1',
                ],
                'attr' => [
                    'placeholder' => 'externalLink.label.url',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ExternalLink::class,
            'attr' => [],
        ]);
    }
}
