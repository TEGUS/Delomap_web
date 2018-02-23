<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateSignature')
            ->add('projet', EntityType::class, [
                'class' => 'AppBundle:Projet',
                'choice_label' => 'libelle',
                'required' => true
            ])
            ->add('dag', EntityType::class, [
                'class' => 'AppBundle:DAG',
                'choice_label' => 'libelle',
                'required' => true
            ])
            ->add('fichier', SimpleFileType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Document'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_document';
    }


}
