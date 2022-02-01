<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class WorkflowForms extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        switch ($options['attr']['formName']) {
            case 'planning':
                $builder
                    ->add('title', TextType::class, ['label' => 'Title'])
                    ->add('content', TextType::class, ['label' => 'Content'])
                    ->add('Start', SubmitType::class, ['label' => 'Start Workflow']);
                break;
            case 'draft':
                $builder
                    ->add('Next', SubmitType::class, ['label' => 'Next']);
                break;
            case ('rejected' || 'published'):
                $builder
                    ->add('rating', ChoiceType::class, [
                        'required' => true,
                        'choices'  => [
                            '' => '',
                            '1' => 1,
                            '2' => 2,
                            '3' => 3,
                            '4' => 4,
                            '5' => 5,
                            '6' => 6,
                            '7' => 7,
                            '8' => 8,
                            '9' => 9,
                            '10' => 10
                        ]
                    ])
                    ->add('rejection', TextType::class, ['label' => 'Rejection Motive'])
                    ->add('Finish', SubmitType::class, ['label' => 'Finish']);
                break;
            default:
                # code...
                break;
        }
    }
}
