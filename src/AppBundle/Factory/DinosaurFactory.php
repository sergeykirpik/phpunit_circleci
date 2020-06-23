<?php

namespace AppBundle\Factory;

use AppBundle\Entity\Dinosaur;

class DinosaurFactory
{
    public function growVelociraptor(int $length): Dinosaur
    {
        return $this->createDinosaur('Velociraptor', true, $length);
    }

    public function growTriceratops(int $length): Dinosaur
    {
        return $this->createDinosaur('Triceratops', false, $length);
    }

    public function growFromSpecification(string $specification): Dinosaur
    {
        // defaults
        $codeName = 'InG-' . random_int(1, 99999);
        $length = $this->getLengthFromSpecification($specification);
        $isCarnivorous = false;

        if (stripos($specification, 'carnivorous') !== false) {
            $isCarnivorous = true;
        }

        $dinosaur = $this->createDinosaur($codeName, $isCarnivorous, $length);

        return $dinosaur;
    }

    private function createDinosaur(string $genus, bool $isCarnivorous, int $length): Dinosaur
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);
        $dinosaur->setLength($length);

        return $dinosaur;
    }

    private function getLengthFromSpecification($specification): int
    {
        $availableLengths = [
            'huge'   => ['min' => Dinosaur::HUGE,  'max' => Dinosaur::MAX_LENGTH],
            'omg'    => ['min' => Dinosaur::HUGE,  'max' => Dinosaur::MAX_LENGTH],
            '😱'     => ['min' => Dinosaur::HUGE,  'max' => Dinosaur::MAX_LENGTH],
            'large'  => ['min' => Dinosaur::LARGE, 'max' => Dinosaur::HUGE - 1],
        ];
        $range = ['min' => Dinosaur::MIN_LENGTH, 'max' => Dinosaur::LARGE - 1];

        foreach (explode(' ', $specification) as $keyword) {
            $keyword = strtolower($keyword);

            if (array_key_exists($keyword, $availableLengths)) {
                $range = $availableLengths[$keyword];
                break;
            }
        }

        return random_int($range['min'], $range['max']);
    }
}
