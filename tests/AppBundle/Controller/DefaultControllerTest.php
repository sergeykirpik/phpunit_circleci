<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadBasicParkData;
use AppBundle\DataFixtures\ORM\LoadSecurityData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class DefaultControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testEnclosuresAreShownOnTheHomepage()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class,
        ]);

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        $table = $crawler->filter('.table-enclosures');
        $this->assertCount(3, $table->filter('tbody tr'));
    }

    public function testThereIsAnAlarmButtonWithoutSecurity()
    {
        $fixtures = $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class,
        ])->getReferenceRepository();

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/');

        // dump($client->getResponse()->getContent());

        $enclosure = $fixtures->getReference('carnivorous-enclosure');
        $selector = sprintf("#enclosure-%s .button-alarm", $enclosure->getId());

        $this->assertGreaterThan(0, $crawler->filter($selector)->count());
    }

    public function testItGrowsADinosaurFromASpecification()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class,
        ]);

        $client = $this->makeClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        $form = $crawler->selectButton('Grow dinosaur')->form();
        $form['enclosure']->select(3);
        $form['specification']->setValue('large herbivore');

        $client->submit($form);

        $this->assertStringContainsString(
            'Grew a large herbivore in enclosure #3',
            $client->getResponse()->getContent()
        );
    }
}
