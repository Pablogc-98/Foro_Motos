<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class, [
                'label'=>'Nombre de Usuario'
            ])

            ->add('password', PasswordType::class,[
                'label'=> 'Contraseña'
            ] )

            ->add('descripcion',TextType::class, [
                'label'=>'Dejanos una descripción de tu'
            ])
            
            ->add('foto', FileType::class,[
                'label'=>'Foto',
                'required'=> false,
            ])
            ->add('Iniciar', SubmitType::class,[
                'label'=> 'Crear Usuario'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
