<?php


namespace App\Form;


use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInfosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('last_name',TextType::class)
            ->add('first_name',TextType::class)
            ->add('username',EmailType::class)
            ->add('mobile', TextType::class)
            ->add('address', TextType::class)
            ->add('city', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('country', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Users::class,
            'translation_domain' => 'forms',
            'method'            => 'get',
            'csrf_protection'   => false
        ));
    }

}