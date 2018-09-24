<?php

namespace Modules\BucketBundle\Forms;

use Symfony\Component\Form\{
    Extension\Core\DataTransformer\DateTimeToStringTransformer,
    FormBuilderInterface,
    AbstractType
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class BucketType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', null,  array('error_bubbling'=>true));
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
        return 'Bucket';
    }
}