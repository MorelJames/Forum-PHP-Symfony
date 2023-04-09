<?php
# Depuis src/Form/PostType.php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, array(
                'class' => 'App\Entity\User',
                'multiple' => false,
                'choice_label' => 'username',
                'placeholder' => 'Select a user'
            ))
            ->add('setRole', ChoiceType::class, [
                'choices'  => [
                    'admin' => 0,
                    'bloger' => 1,
                    'user' => 2,
                ],
                'placeholder' => 'Select a role'])

            ->add('save', SubmitType::class);
    }
}
