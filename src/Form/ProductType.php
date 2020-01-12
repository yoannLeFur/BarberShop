<?php


namespace App\Form;

use App\Entity\Brand;
use App\Entity\Images;
use App\Entity\Product;
use App\Entity\ProductCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType  extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('price', TextType::class)
            ->add('stock', TextType::class)
            ->add('image', FileType::class, [
                'label' => 'Image (jpg, jpeg)',
                'data_class' => null,
                'required' => false,

            ])
            ->add('category', EntityType::class, [
                'class' => ProductCategory::class,
                'required' => false,
                'choice_label' => 'name',
                'attr' => ['data-select' => 'false', 'data-placeholder' => 'Choisir une catégorie']
            ])
            ->add('brand', EntityType::class, [
                'attr' => ['data-select' => 'false', 'data-placeholder' => 'Choisir une marque'],
                'class' => Brand::class,
                'required' => false,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
            'translation_domain' => 'forms'
        ));
    }


}