<?php

namespace App\Test\Controller;

use App\Entity\Medias;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MediasControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/medias/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Medias::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Media index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'media[nom]' => 'Testing',
            'media[preface]' => 'Testing',
            'media[createdAt]' => 'Testing',
            'media[modifedAt]' => 'Testing',
            'media[isArchived]' => 'Testing',
            'media[fichier]' => 'Testing',
            'media[image]' => 'Testing',
            'media[type]' => 'Testing',
            'media[createdBy]' => 'Testing',
            'media[modifedBy]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Medias();
        $fixture->setNom('My Title');
        $fixture->setPreface('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setModifedAt('My Title');
        $fixture->setIsArchived('My Title');
        $fixture->setFichier('My Title');
        $fixture->setImage('My Title');
        $fixture->setType('My Title');
        $fixture->setCreatedBy('My Title');
        $fixture->setModifedBy('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Media');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Medias();
        $fixture->setNom('Value');
        $fixture->setPreface('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setModifedAt('Value');
        $fixture->setIsArchived('Value');
        $fixture->setFichier('Value');
        $fixture->setImage('Value');
        $fixture->setType('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setModifedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'media[nom]' => 'Something New',
            'media[preface]' => 'Something New',
            'media[createdAt]' => 'Something New',
            'media[modifedAt]' => 'Something New',
            'media[isArchived]' => 'Something New',
            'media[fichier]' => 'Something New',
            'media[image]' => 'Something New',
            'media[type]' => 'Something New',
            'media[createdBy]' => 'Something New',
            'media[modifedBy]' => 'Something New',
        ]);

        self::assertResponseRedirects('/medias/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPreface());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getModifedAt());
        self::assertSame('Something New', $fixture[0]->getIsArchived());
        self::assertSame('Something New', $fixture[0]->getFichier());
        self::assertSame('Something New', $fixture[0]->getImage());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getCreatedBy());
        self::assertSame('Something New', $fixture[0]->getModifedBy());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Medias();
        $fixture->setNom('Value');
        $fixture->setPreface('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setModifedAt('Value');
        $fixture->setIsArchived('Value');
        $fixture->setFichier('Value');
        $fixture->setImage('Value');
        $fixture->setType('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setModifedBy('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/medias/');
        self::assertSame(0, $this->repository->count([]));
    }
}
