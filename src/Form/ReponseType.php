<?php

namespace App\Form;

use App\Entity\Questions;
use App\Entity\Reponses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['data']['reponses'] as $name => $reponse) {
            $builder->add($name, TextareaType::class, [
                'label' => $reponse->getIdQuestion()->getQuestion(),
                'required' => false,
                'mapped' => false,
            ]);
        }
        $builder->add('submit', SubmitType::class, [
            'label' => 'Enregistrer les réponses',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
