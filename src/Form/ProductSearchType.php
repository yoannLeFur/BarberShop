<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\ProductCategory;
use App\Entity\ProductSearch;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchType extends AbstractType
{
    private $productRepository;

    public function __construct(ProductCategoryRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

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
            ->add('category', ChoiceType::class, [
                'choices' => $this->getProductCategory(),
                'label' => false,
                'required' => false,
                'choice_label' => 'name',
                'attr' => ['data-select' => 'true', 'data-placeholder' => 'Choisir une catégorie']
            ])
            ->add('brand', EntityType::class, [
                'required' => false,
                'label'    => false,
                'class'    => Brand::class,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => ['data-select' => 'true', 'data-placeholder' => 'Marques']
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

    public function getProductCategory()
    {
        $productCategory = array();
        foreach ($this->productRepository->findAll() as $categories) {
           $productCategory[] = $categories;
        }
        return $productCategory;
    }
}
