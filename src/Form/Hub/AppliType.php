<?php

declare(strict_types=1);

namespace App\Form\Hub;

use App\Entity\Hub\Appli;
use App\Entity\Hub\Category;
use App\Form\DefaultType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AppliType extends DefaultType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'appli.label.title',
                'help' => 'appli.help.title',
                'attr' => [
                    'button_action' => 'clear',
                    'placeholder' => 'appli.label.title',
                ],
            ])
            ->add('category', EntityType::class, [
                'required' => true,
                'class' => Category::class,
                'choice_label' => 'label',
                'label' => 'appli.label.category',
                'attr' => [
                    'placeholder' => 'appli.label.category',
                ],
            ])
            ->add('number', IntegerType::class, [
                'required' => true,
                'label' => 'appli.label.number',
                'attr' => [
                    'placeholder' => 'appli.label.number',
                ],
            ])
            ->add('public', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'enum.choices.yes' => true,
                    'enum.choices.no' => false,
                ],
                'choice_translation_domain' => 'messages',
                'label' => 'appli.label.public',
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'expanded' => true,
                'multiple' => false,
                'row_attr' => [
                    'class' => 'app-form-row-bordered',
                ],
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'appli.label.name',
                'help' => 'appli.help.name',
                'attr' => [
                    'button_action' => 'clear',
                    'placeholder' => 'appli.label.name',
                ],
            ])
            ->add('path', TextType::class, [
                'required' => true,
                'label' => 'appli.label.path',
                'help' => 'appli.help.path',
                'attr' => [
                    'button_action' => 'clear',
                    'placeholder' => 'appli.label.path',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'appli.label.description',
                'attr' => [
                    'button_action' => 'clear',
                    'placeholder' => 'appli.label.description',
                ],
            ])
            ->add('linkText', TextType::class, [
                'required' => true,
                'label' => 'appli.label.linkText',
                'attr' => [
                    'button_action' => 'clear',
                    'placeholder' => 'appli.label.linkText',
                ],
            ])
            ->add('externalLinks', CollectionType::class, [
                'required' => false,
                'label' => 'appli.label.externalLinks',
                'entry_type' => ExternalLinkType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'row_attr' => [
                    'class' => 'app-form-row-bordered',
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => true,
                'label' => 'appli.label.imageFile',
                'help' => 'appli.help.image',
                'help_html' => true,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => true,
                'row_attr' => [
                    'class' => 'app-form-row-bordered',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Appli::class,
            'attr' => [
                'data-controller' => 'button-action',
            ],
        ]);
    }
}
