<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Trainer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $course = $event->getData();
            $form = $event->getForm();
            if ($course  && $course->getFilename()) {
                $form->add('deleteCb', CheckboxType::class, [
                    'mapped' => false,
                    'required' => false,
                    'label' => 'check me to delete this image'
                ]);
            }
        });

        $builder
            ->add('name',TextType::class,['label' => 'Titre du cours'])
            ->add('content', TextareaType::class, ['label' => 'Description','required' => false])
            ->add('duration',TextType::class,['label' => 'Durée du cours'])
            ->add('published', CheckboxType::class,['label' => 'Publié','required' => false])
            ->add('file',FileType::class, [
                'label' => 'Choisir un image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image(
                        [
                            'maxSize' => '16M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Upload a jpg et png image'
                        ]
                    )
                ]
            ])
        ->add('category', EntityType::class, [
            'label' => 'Catégorie',
            'class' => Category::class,
            'choice_label' => 'name',
            'placeholder' => 'Veuillez choisir ...'
        ])
            ->add('trainers', EntityType::class, [
                'label' => 'Formateurs',
                'class' => Trainer::class,
                'choice_label' => 'fullname',
                'placeholder' => 'Veuillez choisir ...',
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
