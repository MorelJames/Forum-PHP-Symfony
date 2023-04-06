<?php
# Depuis src/Form/PostType.php
namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ChooseCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Category', EntityType::class, array(
                'class' => 'App\Entity\Category',
                'multiple' => false,
                'choice_label' => 'name',
                'placeholder' => 'Choose a category'
            ))
            ->add('save', SubmitType::class);
    }
}
