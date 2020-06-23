<?php

namespace Tests\AppBundle\Factory;

use AppBundle\Entity\Dinosaur;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\TestCase;

class DinosaurFactoryTest extends TestCase
{
    /**
     * @var DinosaurFactory
     */
    private $factory;

    public function setUp(): void
    {
        $mockLengthDeterminator = $this->createMock(DinosaurLengthDeterminator::class);
        $this->factory = new DinosaurFactory($mockLengthDeterminator);
    }

    public function testItGrowsAVelociraptor()
    {
        $dinosaur = $this->factory->growVelociraptor(5);

        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
        $this->assertIsString($dinosaur->getGenus());
        $this->assertSame('Velociraptor', $dinosaur->getGenus());
        $this->assertSame(5, $dinosaur->getLength());
        $this->assertTrue($dinosaur->isCarnivorous());
    }

    public function testItGrowsATriceratops()
    {
        $this->markTestIncomplete('Waiting for confirmation from GenLab');
        $dinosaur = $this->factory->growTriceratops(10);

        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
        $this->assertSame('Triceratops', $dinosaur->getGenus());
        $this->assertFalse($dinosaur->isCarnivorous());
        $this->assertSame(10, $dinosaur->getLength());
    }

    public function testItGrowsABabyVelociraptor()
    {
        if (!class_exists('Nanny')) {
            $this->markTestSkipped('There is nobody to watch the baby');
        }
        $dinosour = $this->factory->growVelociraptor(1);
        $this->assertSame(1, $dinosour->getLength());
    }

    /**
     * @dataProvider getSpecificationTests
     */
    public function testItGrowsADinosourFromASpecification(string $spec, bool $expectedIsCarnivorous)
    {
        $dinosaur = $this->factory->growFromSpecification($spec);

        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
        $this->assertSame($expectedIsCarnivorous, $dinosaur->isCarnivorous(), 'Diets do not match');
    }

    public function getSpecificationTests()
    {
        return [
            // specification, is carnivorous
            ['large carnivorous dinosaur', true],
            'default response' => ['give me all the cookies!!!', false],
            ['large herbivore', false]
        ];
    }

}
