<?php
namespace Docs\MainBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Rating type form
 * @author hbotev
 */
class RatingForm extends AbstractType
{
    /**
     * Builds the rating form
     * @param  \Symfony\Component\Form\FormBuilder $builder
     * @param  array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Enter your opinion here...',
                    'class' => 'animated',
                    'id' => 'new-review'
                ]
            ])
            ->add('rating', HiddenType::class, [
                'attr' => ['id' => 'ratings-hidden']
            ])
            ->add('fromUser', HiddenType::class)
            ->add('userID', HiddenType::class)
            ->add('save', SubmitType::class , ['label' => 'Save'])
            ->getForm();
    }
}
