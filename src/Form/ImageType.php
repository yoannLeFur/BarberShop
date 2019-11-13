<?php


namespace App\Form;

use App\Entity\Images;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType  extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'placeholder' => 'choisir un produit',
                'class' => Product::class,
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (jpg, jpeg)',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Images::class,
            'translation_domain' => 'forms'
        ));
    }

}