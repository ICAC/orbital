<?php

namespace AppBundle\Form\Type;

use AppBundle\Services\Enum\BowType;
use AppBundle\Services\Enum\Skill;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScoreType extends AbstractProofType
{
    private $edit;

    public function __construct($edit = false)
    {
        $this->edit = $edit;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mode = $builder->create('mode', 'form', ['inherit_data' => true, 'label' => false, 'attr' => ['orbital-collapse' => 'Skill / Bowtype']])
            ->add('skill', 'choice', [
                'choices' => Skill::$choices,
                'required' => false,
            ])
            ->add('bowtype', 'choice', [
                'choices' => BowType::$choices,
                'required' => false,
            ]);

        $score = $builder->create('score', 'form', ['inherit_data' => true, 'label' => false, 'attr' => ['class' => 'inline']])
            ->add('score', 'integer', ['required' => false])
            ->add('golds', 'integer', ['required' => false])
            ->add('hits', 'integer', ['required' => false]);

        $checks = $builder->create('checks', 'form', ['inherit_data' => true, 'label' => false, 'attr' => ['class' => 'inline']])
            ->add('competition', 'checkbox', ['required' => false, 'label' => 'Was it shot at a competition?'])
            ->add('complete', 'checkbox', ['required' => false, 'label' => 'Have you completed it?']);

        $builder
            ->add('person', 'entity', [
                'class' => 'AppBundle:Person',
                'disabled' => $this->edit
            ])
            ->add($mode)
            ->add('round', 'entity', [
                'class' => 'AppBundle:Round',
                'disabled' => $this->edit
            ])
            ->add($score)
            ->add($checks)
            ->add('date_shot', 'datetime', ['label' => 'When was (or will be) this shot?'])
            ->add($this->getProofForm($builder));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Score'
        ]);
    }

    public function getName()
    {
        return 'score';
    }
}
