<?php

namespace AppBundle\Service;

use AppBundle\Entity\Dinosaur;

class DinosaurLengthDeterminator
{
    public function getLengthFromSpecification($specification): int
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
