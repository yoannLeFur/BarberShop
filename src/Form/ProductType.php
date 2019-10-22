<?php


namespace App\Form;


use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\Property;
use App\Entity\Users;
use App\Repository\BrandRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType  extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name')
            ->add('description')
            ->add('price')
            ->add('stock')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
            'translation_domain' => 'forms'
        ));
    }

    public function getCategory()
    {
        $choices = ProductCategory::class;
        return $choices;

    }

    public function getBrand()
    {
        $choices = BrandRepository::class;
        return $choices;
    }

}