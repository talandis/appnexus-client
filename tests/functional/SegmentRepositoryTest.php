<?php

namespace Test\functional;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\authentication\AdnxStrategy;
use Audiens\AppnexusClient\entity\Segment;
use Audiens\AppnexusClient\facade\AppnexusFacade;
use Audiens\AppnexusClient\repository\SegmentRepository;
use Doctrine\Common\Cache\FilesystemCache;
use GuzzleHttp\Client;
use Prophecy\Argument;
use Test\FunctionalTestCase;

/**
 * Class SegmentRepositoryTest
 */
class SegmentRepositoryTest extends FunctionalTestCase
{

    /**
     * @test
     */
    public function add_will_create_a_new_segment_and_return_a_repository_response()
    {

        $repository = new SegmentRepository($this->getAuth());

        $segment = new Segment();
        $segment->setName('Test segment'.uniqid());
        $segment->setCategory('Test category'.uniqid());
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repositoryResponse = $repository->add($segment);

        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());
        $this->assertNotNull($segment->getId());


        // FACADE
        $facade = $this->getFacade();

        $segment = new Segment();
        $segment->setName('Test segment'.uniqid());
        $segment->setCategory('Test category'.uniqid());
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repositoryResponse = $facade->add($segment);

        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());
        $this->assertNotNull($segment->getId());

    }

    /**
     * @test
     */
    public function find_one_by_id_will_retrive_a_newly_created_segment()
    {
        $repository = new SegmentRepository($this->getAuth());

        $segment = new Segment();

        $segment->setName('Test segment '.uniqid());
        $segment->setCategory('a-test-category');
        $segment->setDescription('a test description 123456');
        $segment->setCode(rand(0, 999) * rand(0, 999));
        $segment->setPrice(10.11);
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repository->add($segment);

        $segmentFound = $repository->findOneById($segment->getMemberId(), $segment->getId());

        $this->assertNotNull($segmentFound, 'find one by id segment not found');

        $this->assertEquals($segment->getId(), $segmentFound->getId());
        $this->assertEquals($segment->getDescription(), $segmentFound->getDescription());


        //FACADE
        $facade = $this->getFacade();

        $segment = new Segment();

        $segment->setName('Test segment '.uniqid());
        $segment->setCategory('a-test-category');
        $segment->setDescription('a test description 123456');
        $segment->setCode(rand(0, 999) * rand(0, 999));
        $segment->setPrice(10.11);
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $facade->add($segment);

        $segmentFound = $facade->findOneById($segment->getId());

        $this->assertNotNull($segmentFound, 'find one by id segment not found');

        $this->assertEquals($segment->getId(), $segmentFound->getId());
        $this->assertEquals($segment->getDescription(), $segmentFound->getDescription());

    }

    /**
     * @test
     */
    public function find_all_will_return_multiple_segments()
    {

        $repository = new SegmentRepository($this->getAuth());

        $segment = new Segment();

        $segment->setName('Test segment '.uniqid());
        $segment->setCategory('a-test-category');
        $segment->setDescription('a test description 123456');
        $segment->setCode(rand(0, 999) * rand(0, 999));
        $segment->setPrice(10.11);
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repository->add($segment);

        $segments = $repository->findAll(getenv('MEMBER_ID'), 0, 2);

        $this->assertCount(2, $segments);

        foreach ($segments as $segment) {
            $this->assertInstanceOf(Segment::class, $segment);
        }

        //FACADE
        $facade = $this->getFacade();

        $segment = new Segment();

        $segment->setName('Test segment '.uniqid());
        $segment->setCategory('a-test-category');
        $segment->setDescription('a test description 123456');
        $segment->setCode(rand(0, 999) * rand(0, 999));
        $segment->setPrice(10.11);
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repository->add($segment);

        $segments = $facade->findAll(0, 2);

        $this->assertCount(2, $segments);

        foreach ($segments as $segment) {
            $this->assertInstanceOf(Segment::class, $segment);
        }

    }

    /**
     * @test
     */
    public function delete_will_remove_a_segment()
    {

        $repository = new SegmentRepository($this->getAuth());

        $segment = new Segment();

        $segment->setName('Test segment '.uniqid());
        $segment->setCategory('a-test-category');
        $segment->setDescription('a test description 123456');
        $segment->setCode(rand(0, 999) * rand(0, 999));
        $segment->setPrice(10.11);
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repositoryResponse = $repository->add($segment);

        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());

        $repositoryResponse = $repository->remove($segment->getMemberId(), $segment->getId());

        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());


        //FACADE
        $facade = $this->getFacade();

        $segment = new Segment();

        $segment->setName('Test segment '.uniqid());
        $segment->setCategory('a-test-category');
        $segment->setDescription('a test description 123456');
        $segment->setCode(rand(0, 999) * rand(0, 999));
        $segment->setPrice(10.11);
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repositoryResponse = $facade->add($segment);

        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());

        $repositoryResponse = $facade->remove($segment->getId());

        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());


    }

    /**
     * @test
     */
    public function update_will_edit_an_existing_segment()
    {

        $repository = new SegmentRepository($this->getAuth());

        $segment = new Segment();

        $segment->setName('Test segment '.uniqid());
        $segment->setCategory('a-test-category');
        $segment->setDescription('a test description 123456');
        $segment->setCode(rand(0, 999) * rand(0, 999));
        $segment->setPrice(10.11);
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repositoryResponse = $repository->add($segment);
        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());

        $segment->setPrice(12.11);

        $repositoryResponse = $repository->update($segment);
        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());

        $this->assertEquals(12.11, $segment->getPrice());


        //FACADE
        $facade = $this->getFacade();

        $segment = new Segment();

        $segment->setName('Test segment '.uniqid());
        $segment->setCategory('a-test-category');
        $segment->setDescription('a test description 123456');
        $segment->setCode(rand(0, 999) * rand(0, 999));
        $segment->setPrice(10.11);
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repositoryResponse = $facade->add($segment);
        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());

        $segment->setPrice(12.11);

        $repositoryResponse = $facade->update($segment);
        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());

        $this->assertEquals(12.11, $segment->getPrice());


    }


    /**
     * @param bool|true $cacheToken
     *
     * @return Auth
     */
    protected function getAuth($cacheToken = true)
    {

        $cache = $cacheToken ? new FilesystemCache('build') : null;
        $client = new Client();

        $authStrategy = new AdnxStrategy(new Client(), $cache);

        $authClient = new Auth(getenv('USERNAME'), getenv('PASSWORD'), $client, $authStrategy);

        return $authClient;

    }


    /**
     * @return AppnexusFacade
     */
    protected function getFacade()
    {

        $facade = new AppnexusFacade(getenv('USERNAME'), getenv('PASSWORD'), getenv('MEMBER_ID'));

        return $facade;

    }


}
