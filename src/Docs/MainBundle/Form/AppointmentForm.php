<?php
namespace Docs\MainBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\Callback;
use Docs\MainBundle\Form\Validators\PastDateValidator;
use Docs\CommonBundle\Entity\Symptom;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentForm extends AbstractType
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var integer
     */
    protected $userID;

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setOptions($options);
        $pastDate = new PastDateValidator();

        $builder->add('scheduled', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'choice',
                'hours' => range(7, 19),
                'html5' => false,
                'minutes' => range(0, 59, 5),
                'constraints' => [
                    new Callback(
                        ['callback' => [$pastDate, 'validate']]
                    )
                ]
        ])
        ->add('withUser', ChoiceType::class, [
            'required' => true,
            'choices' => $this->getDocs(),
            'data' => $this->userID ? :'',
            'choices_as_values' => true
        ])
        ->add('content', TextareaType::class , ['required' => false, 'label' => "Specific complains"])
        ->add('symptoms', ChoiceType::class, [
            'required' => false,
            'choices' => $this->getSymptoms(),
            'multiple' => true,
            'choices_as_values' => true
        ])
        ->add('noHealthInsurance', CheckboxType::class , ['required' => false])
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
            'userID' => '',
            'entityManager' => null
        ]);
    }

    /**
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->entityManager = $options['entityManager'];
        $this->userID = $options['userID'];
    }

    /**
     * Return array of agreement result types for the select
     * @return array
     */
    protected function getDocs()
    {
        $usersRepo = $this->entityManager->getRepository(
            "\Docs\CommonBundle\Entity\User"
        );

        $mds = $usersRepo->getAllActiveDocs();

        $mdsArray = [];
        foreach ($mds as $md) {
            $user = $md->getUser();
            $mdsArray[$user->getFirstName() . ' ' . $user->getLastName()] = $user->getUserID();
        }

        return $mdsArray;
    }

    /**
     * Get common symptoms from db
     */
    protected function getSymptoms()
    {
        $symptomsRepo = $this->entityManager->getRepository(
            "\Docs\CommonBundle\Entity\Symptom"
        );

        $symptoms = $symptomsRepo->findAll();
        $data = [];
        foreach ($symptoms as $symptom) {
            $data[$symptom->getName()] = $symptom->getSymptomID();
        }

        return $data;
    }
}
