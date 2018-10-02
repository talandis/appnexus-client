<?php

namespace Test\functional;

use Audiens\AppnexusClient\entity\Segment;
use Audiens\AppnexusClient\entity\SegmentBilling;
use Test\FunctionalTestCase;

class SegmentBillingRepositoryTest extends FunctionalTestCase
{

    /**
     * @test
     */
    public function add_will_create_a_new_segment_billing__and_return_a_repository_response()
    {
        $this->markTestSkipped('TODO Sandbox endpoint bug, Unable to process request');

        $repositorySegment = $this->getSegmentRepository();

        $segment = new Segment();
        $segment->setName('Test segment'.\uniqid('', true));
        $segment->setActive(true);

        $repositoryResponseSegment = $repositorySegment->add($segment);

        $this->assertTrue($repositoryResponseSegment->isSuccessful(), (string)$repositoryResponseSegment->getError()->getError());
        $this->assertNotNull($segment->getId());

        $repository = $this->getSegmentBillingRepository();

        $segmentBilling = new SegmentBilling();
        $segmentBilling->setActive(true);
        $segmentBilling->setDataCategoryId(getenv('DATA_CATEGORY_ID'));
        $segmentBilling->setDataProviderId(getenv('DATA_PROVIDER_ID'));
        $segmentBilling->setSegmentId($segment->getId());
        $segmentBilling->setIsPublic(false);

        $repositoryResponse = $repository->add($segmentBilling);

        $this->assertTrue($repositoryResponse->isSuccessful(), (string)$repositoryResponse->getError()->getError());
        $this->assertNotNull($segmentBilling->getId());
    }

    /**
     * @test
     */
    public function find_one_by_id_will_retrive_a_newly_created_billing_segment()
    {
        $this->markTestSkipped('TODO Sandbox endpoint bug, Unable to process request');

        $repositorySegment = $this->getSegmentRepository();

        $segment = new Segment();
        $segment->setName('Test segment'.\uniqid('', true));
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repositorySegment->add($segment);

        $this->assertNotNull($segment->getId());

        $repository = $this->getSegmentBillingRepository();

        $segmentBilling = new SegmentBilling();
        $segmentBilling->setActive(true);
        $segmentBilling->setDataCategoryId(getenv('DATA_CATEGORY_ID'));
        $segmentBilling->setDataProviderId(getenv('DATA_PROVIDER_ID'));
        $segmentBilling->setIsPublic(true);
        $segmentBilling->setSegmentId($segment->getId());

        $repositoryResponse = $repository->add($segmentBilling);

        $this->assertTrue($repositoryResponse->isSuccessful(), (string)$repositoryResponse->getError()->getError());

        $this->assertNotNull($segmentBilling->getId());

        $segmentFound = $repository->findOneBySegmentId($segmentBilling->getSegmentId());

        $this->assertNotNull($segmentFound, 'find one by id segment not found');

        $this->assertEquals($segmentBilling->getId(), $segmentFound->getId());
    }

    /**
     * @test
     */
    public function find_all_will_return_multiple_billing_segments()
    {
        $this->markTestSkipped('TODO Sandbox endpoint bug, Unable to process request');

        $repository = $this->getSegmentBillingRepository();

        $segments = $repository->findAll(0, 100);

        foreach ($segments as $segment) {
            $this->assertInstanceOf(SegmentBilling::class, $segment);
            $this->assertNotNull($segment->getId());
        }
    }

    /**
     * @test
     */
    public function update_will_edit_an_existing_segment()
    {
        $this->markTestSkipped('TODO Sandbox endpoint bug, Unable to process request');

        $repositorySegment = $this->getSegmentRepository();

        $segment = new Segment();
        $segment->setName('Test segment'.uniqid('', true));
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repositoryResponseSegment = $repositorySegment->add($segment);

        $this->assertTrue($repositoryResponseSegment->isSuccessful(), (string)$repositoryResponseSegment->getError()->getError());
        $this->assertNotNull($segment->getId());

        $repository = $this->getSegmentBillingRepository();

        $segmentBilling = new SegmentBilling();
        $segmentBilling->setActive(true);
        $segmentBilling->setDataCategoryId(getenv('DATA_CATEGORY_ID'));
        $segmentBilling->setDataProviderId(getenv('DATA_PROVIDER_ID'));
        $segmentBilling->setSegmentId($segment->getId());
        $segmentBilling->setIsPublic(true);

        $repositoryResponse = $repository->add($segmentBilling);

        $this->assertTrue($repositoryResponse->isSuccessful(), (string)$repositoryResponse->getError()->getError());

        $segmentBilling->setIsPublic(false);

        $repositoryResponse = $repository->update($segmentBilling);

        $this->assertTrue($repositoryResponse->isSuccessful(), (string)$repositoryResponse->getError()->getError());

        $segmentBillingUpdated = $repository->findOneBySegmentId($segmentBilling->getSegmentId());

        $this->assertEquals($segmentBilling->getId(), $segmentBillingUpdated->getId());
        $this->assertEquals(false, $segmentBillingUpdated->getIsPublic());
    }

    /**
     * @test
     */
    public function delete_will_remove_a_segment()
    {
        $this->markTestSkipped('TODO Sandbox endpoint bug, Unable to process request');

        $repositorySegment = $this->getSegmentRepository();

        $segment = new Segment();
        $segment->setName('Test segment'.uniqid('', true));
        $segment->setMemberId(getenv('MEMBER_ID'));
        $segment->setActive(true);

        $repositoryResponseSegment = $repositorySegment->add($segment);

        $this->assertTrue($repositoryResponseSegment->isSuccessful(), (string)$repositoryResponseSegment->getError()->getError());
        $this->assertNotNull($segment->getId());

        $repository = $this->getSegmentBillingRepository();

        $segmentBilling = new SegmentBilling();
        $segmentBilling->setActive(true);
        $segmentBilling->setDataCategoryId(getenv('DATA_CATEGORY_ID'));
        $segmentBilling->setDataProviderId(getenv('DATA_PROVIDER_ID'));
        $segmentBilling->setSegmentId($segment->getId());
        $segmentBilling->setIsPublic(true);

        $repositoryResponse = $repository->add($segmentBilling);

        $this->assertTrue($repositoryResponse->isSuccessful(), (string)$repositoryResponse->getError()->getError());

        $repositoryResponse = $repository->remove($segmentBilling->getId());

        $this->assertTrue($repositoryResponse->isSuccessful(), (string)$repositoryResponse->getError()->getError());
    }

}
