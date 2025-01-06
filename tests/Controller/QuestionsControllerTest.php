<?php

namespace App\Test\Controller;

use App\Entity\Questions;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuestionsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/questions/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Questions::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Question index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'question[contenu]' => 'Testing',
            'question[createdAt]' => 'Testing',
            'question[reponse]' => 'Testing',
            'question[modifiedAt]' => 'Testing',
            'question[answeredAt]' => 'Testing',
            'question[isVisible]' => 'Testing',
            'question[demandeur]' => 'Testing',
            'question[repondeur]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Questions();
        $fixture->setContenu('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setReponse('My Title');
        $fixture->setModifiedAt('My Title');
        $fixture->setAnsweredAt('My Title');
        $fixture->setIsVisible('My Title');
        $fixture->setDemandeur('My Title');
        $fixture->setRepondeur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Question');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Questions();
        $fixture->setContenu('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setReponse('Value');
        $fixture->setModifiedAt('Value');
        $fixture->setAnsweredAt('Value');
        $fixture->setIsVisible('Value');
        $fixture->setDemandeur('Value');
        $fixture->setRepondeur('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'question[contenu]' => 'Something New',
            'question[createdAt]' => 'Something New',
            'question[reponse]' => 'Something New',
            'question[modifiedAt]' => 'Something New',
            'question[answeredAt]' => 'Something New',
            'question[isVisible]' => 'Something New',
            'question[demandeur]' => 'Something New',
            'question[repondeur]' => 'Something New',
        ]);

        self::assertResponseRedirects('/questions/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getContenu());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getReponse());
        self::assertSame('Something New', $fixture[0]->getModifiedAt());
        self::assertSame('Something New', $fixture[0]->getAnsweredAt());
        self::assertSame('Something New', $fixture[0]->getIsVisible());
        self::assertSame('Something New', $fixture[0]->getDemandeur());
        self::assertSame('Something New', $fixture[0]->getRepondeur());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Questions();
        $fixture->setContenu('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setReponse('Value');
        $fixture->setModifiedAt('Value');
        $fixture->setAnsweredAt('Value');
        $fixture->setIsVisible('Value');
        $fixture->setDemandeur('Value');
        $fixture->setRepondeur('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/questions/');
        self::assertSame(0, $this->repository->count([]));
    }
}
