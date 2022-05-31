<?php

namespace App\Form;

use App\Form\DataTransformer\VilleFranceTransformer;
use App\Repository\ListeVilleFranceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class AutoCompleteTextType extends AbstractType
{
    /**
     * @var ListeVilleFranceRepository
     */
    private $listeVilleFranceRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(ListeVilleFranceRepository $listeVilleFranceRepository, RouterInterface $router)
    {
        $this->listeVilleFranceRepository = $listeVilleFranceRepository;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new VilleFranceTransformer($this->listeVilleFranceRepository));
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message'=>'Ville non valide',
            'help'=>"Veuillez selectionner l'un des champs proposé après 3 caractères",
            'attr'=> [
                'class'=>'js-ville-autocomplete',
                'data-autocomplete-url'=>$this->router->generate('app_api_listevilles')
            ]
        ]);
    }




}