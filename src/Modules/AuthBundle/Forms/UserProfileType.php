<?php

namespace Modules\AuthBundle\Forms;

use DataTransformers\ObjectTransformer;
use Modules\GeoCatalogBundle\Repository\CityRepository;
use Symfony\Component\Form\{
    FormBuilderInterface,
    AbstractType
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastName', null,  array('error_bubbling'=>true));
        $builder->add('name', null,  array('error_bubbling'=>true));
        $builder->add($builder->create('city', null, array('error_bubbling' => true))->addModelTransformer(new ObjectTransformer(CityRepository::get())));
        $builder->add('call', null,  array('error_bubbling'=>true));
        $builder->add('allowCalls', null, array('error_bubbling' => true, 'mapped' => false));
        $builder->add('currentLocation', null, array('error_bubbling' => true, 'mapped' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'UserProfile';
    }
}