<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\ProductCategory;
use App\Entity\ProductSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrice', IntegerType::class, [
                'required' => false,
                'label'    => false,
                'attr'     => [
                    'placeholder' => 'Prix max'
                ]
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'label'    => false,
                'class'    => ProductCategory::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('brand', EntityType::class, [
                'required' => false,
                'label'    => false,
                'class'    => Brand::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => ProductSearch::class,
            'method'            => 'get',
            'csrf_protection'   => false
        ]);
    }

    public function getBlockPrefixe()
    {
        return '';
    }
}
