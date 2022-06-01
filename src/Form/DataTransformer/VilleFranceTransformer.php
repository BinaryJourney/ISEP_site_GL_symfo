<?php

namespace App\Form\DataTransformer;

use App\Repository\ListeVilleFranceRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class VilleFranceTransformer implements DataTransformerInterface
{
    /**
     * @var ListeVilleFranceRepository
     */
    private $listeVilleFranceRepository;

    public function __construct(ListeVilleFranceRepository $listeVilleFranceRepository)
    {
        $this->listeVilleFranceRepository = $listeVilleFranceRepository;
    }

    public function reverseTransform($value)
    {
        if(!$value) {
            return;
        }

        $value = explode(" ", $value)[0];
        $listeVille = $this->listeVilleFranceRepository->findOneBy(['name' => $value]);

        if(!$listeVille) {
            throw new TransformationFailedException(sprintf(
                'Pas de ville trouvé avec le nom "%s"',
                $value
            ));
        }

        return $listeVille;
    }

    public function transform($value)
    {
        // Nothing
    }


}