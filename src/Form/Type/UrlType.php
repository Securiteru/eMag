<?php


namespace App\Form\Type;


use App\Entity\Url as UrlEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Url;

class UrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'link',
                TextType::class,
                [
                    'constraints' => [
                        new NotNull(
                            [
                                'message' => 'Url can not be blank',
                            ]
                        ),
                        new Url(
                            [
                                'message' => "Url has to be in the {{ protocol }}://{{ link }} format",
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'link_type',
                ChoiceType::class,
                [
                    'choices' => [
                        "product" => "product",
                        "category" => "category",
                        "static-page" => "static-page",
                        "checkout" => "checkout",
                        "homepage" => "homepage",
                    ],
                    "required" => true,
                    'invalid_message' => "Link type invalid: Your link types options are: product, category, static-page, checkout, homepage",
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UrlEntity::class,
            ]
        );
    }
}