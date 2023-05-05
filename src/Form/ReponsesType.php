<?php

namespace App\Form;

use App\Entity\Reponses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponsesType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
$reponse = $options['reponse'];

$builder
->add('annotation', TextareaType::class, [
'required' => false,
'label' => false,
'attr' => [
'class' => 'form-control',
],
'data' => $reponse->getAnnotationQuestion(),
])
->add('note', TextType::class, [
'required' => false,
'label' => false,
'attr' => [
'class' => 'form-control',
],
'data' => $reponse->getNoteReponse(),
]);
}

public function configureOptions(OptionsResolver $resolver)
{
$resolver->setDefaults([
'data_class' => Reponses::class,
'reponse' => null,
]);
}
}