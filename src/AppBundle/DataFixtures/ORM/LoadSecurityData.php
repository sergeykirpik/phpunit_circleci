<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Enclosure;
use AppBundle\Entity\Security;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadSecurityData extends AbstractFixture implements ORMFixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $herbivorousEnclosure = $this->getReference('herbivorous-enclosure');
        $this->addSecurity($herbivorousEnclosure, 'Fence', true);

        $carnivorousEnclosure = $this->getReference('carnivorous-enclosure');
        $this->addSecurity($carnivorousEnclosure, 'Electric fence', false);
        $this->addSecurity($carnivorousEnclosure, 'Guard tower', false);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

    private function addSecurity(
        Enclosure $enclosure,
        string $name,
        bool $isActive
    )
    {
        $security = new Security($name, $isActive, $enclosure);
        $enclosure->addSecurity($security);
    }
}

