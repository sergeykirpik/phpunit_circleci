<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Dinosaur;
use AppBundle\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\TestCase;

class DinosaurLengthDeterminatorTest extends TestCase
{
    /**
     * @dataProvider getSpecLengthTests
     */
    public function testItReturnsCorrectLengthRange($spec, $minExpectedSize, $maxExpectedSize)
    {
        $determinator = new DinosaurLengthDeterminator();
        $actualSize = $determinator->getLengthFromSpecification($spec);

        $this->assertGreaterThanOrEqual($minExpectedSize, $actualSize);
        $this->assertLessThanOrEqual($maxExpectedSize, $actualSize);
    }

    public function getSpecLengthTests()
    {
        return [
            // specification, min_length, max_length
            ['large carnivorous dinosaur', Dinosaur::LARGE, Dinosaur::HUGE - 1],
            'default response' => ['give me all the cookies!!!', Dinosaur::MIN_LENGTH, Dinosaur::LARGE - 1],
            ['large herbivore', Dinosaur::LARGE, Dinosaur::HUGE - 1],
            ['huge dinosaur', Dinosaur::HUGE, Dinosaur::MAX_LENGTH],
            ['huge dino', Dinosaur::HUGE, Dinosaur::MAX_LENGTH],
            ['huge', Dinosaur::HUGE, Dinosaur::MAX_LENGTH],
            ['OMG', Dinosaur::HUGE, Dinosaur::MAX_LENGTH],
            ['😱', Dinosaur::HUGE, Dinosaur::MAX_LENGTH]
        ];
    }
}
