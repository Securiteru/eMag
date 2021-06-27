<?php


namespace App\Form\Type;


use App\Entity\AccessLog;
use App\Entity\Customer;
use App\Entity\Url;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class AccessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'customer',
                EntityType::class,
                [
                    'class' => Customer::class,
                    'constraints' => [
                        new NotNull(
                            [
                                'message' => 'Customer Id cannot be null',
                            ]
                        ),

                    ],
                ]
            )
            ->add(
                'timestamp',
                DateTimeType::class,
                [
                    'widget' => "single_text",
                    'constraints' => [
                        new NotNull(
                            [
                                'message' => 'Timestamps cannot be null',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'url',
                EntityType::class,
                [
                    'class' => Url::class,
                    'constraints' => [
                        new NotNull(
                            [
                                'message' => 'Url cannot be null',
                            ]
                        ),
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => AccessLog::class,
            ]
        );
    }
}