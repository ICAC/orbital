<?php


namespace AppBundle\Form;

use AppBundle\Services\Enum\BadgeCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BadgeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', 'textarea')
            ->add('algo_name') // TODO ENUM
            ->add('category', 'choice', [
                'choices' => BadgeCategory::$choices
            ])
            ->add('multiple', 'checkbox', [
                'required' => false
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Badge'
        ));
    }

    public function getName()
    {
        return 'badge';
    }
}