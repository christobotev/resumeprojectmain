<?php
namespace Docs\MainBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\Callback;
use Docs\MainBundle\Form\Validators\PastDateValidator;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Docs\CommonBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Reminder type form
 * @author hbotev
 */
class ReminderForm extends AbstractType
{

    protected $user;

    /**
     * Builds the Reminder form
     * @param  \Symfony\Component\Form\FormBuilder $builder
     * @param  array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setLoggedUser($options['md']);

        $pastDate = new PastDateValidator();
        $builder
            ->add('datetime', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'constraints' => [
                    new Callback(
                        ['callback' => [$pastDate, 'validate']]
                    )
                ]
            ])
            ->add('md', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    $this->user->getFirstName() . ' ' . $this->user->getLastName() => $this->user->getUserID()
                ],
                'attr' => [
                    'value' => $this->user->getFirstName() . ' ' . $this->user->getLastName(),
                    'readonly' => 'readonly'
                ],
                'label' => 'M.D.',
                'choices_as_values' => true
            ])
            ->add('note', TextareaType::class, ['required' => true, 'label' => 'Note'])
            ->add('save', SubmitType::class , ['label' => 'Save'])
            ->getForm();
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'md' => new User(),
        ]);
    }

    /**
     * @param User $user
     */
    protected function setLoggedUser(User $user)
    {
        $this->user = $user;
    }
}
