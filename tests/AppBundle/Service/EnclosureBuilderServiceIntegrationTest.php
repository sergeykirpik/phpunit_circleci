<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Entity\Security;
use AppBundle\Service\EnclosureBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        $this->truncateEntities([
            Enclosure::class,
            Security::class,
            Dinosaur::class
        ]);
    }

    public function testItBuildsEnclosureWithDefaultSpecification()
    {
        /** @var EnclosureBuilderService */
        $enclosureBuilderService = self::$kernel->getContainer()
            ->get('test.'.EnclosureBuilderService::class);

        $enclosureBuilderService->buildEnclosure();

        $em = $this->getEntityManager();

        $count = (int) $em->getRepository(Security::class)
            ->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(1, $count, 'The amount of security systems is not the same');

        $count = (int) $em->getRepository(Dinosaur::class)
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(3, $count, 'The amount of dinosaurus is not the same');
    }

    private function truncateEntities(array $entities)
    {
        $connection = $this->getEntityManager()->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();

        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }

        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $this->getEntityManager()->getClassMetadata($entity)->getTableName()
            );
            $connection->executeUpdate($query);
        }

        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
