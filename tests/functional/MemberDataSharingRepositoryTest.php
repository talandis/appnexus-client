<?php

namespace Test\functional;

use Audiens\AppnexusClient\entity\MemberDataSharing;
use Audiens\AppnexusClient\entity\MemberDataSharingSegment;
use Test\FunctionalTestCase;

class MemberDataSharingRepositoryTest extends FunctionalTestCase
{
    /**
     * @test
     */
    public function find_all_will_return_multiple_member_data_sharing()
    {
        $sharings = $this->getMemberDataSharingRepository()->findAll(0, 5);

        foreach ($sharings as $sharing) {
            $this->assertInstanceOf(MemberDataSharing::class, $sharing);
        }
    }

    /**
     * @test
     */
    public function findByBuyerId_will_return_correnct_member_data_sharing()
    {
        $repository = $this->getMemberDataSharingRepository();

        $sharings = $repository->findByBuyerId(getenv('BUYER_ID'), getenv('MEMBER_ID'));

        $this->assertCount(1, $sharings);

        $sharing = $sharings[0];

        $this->assertInstanceOf(MemberDataSharing::class, $sharing);

        $this->assertEquals($sharing->getDataMemberId(), getenv('MEMBER_ID'));
        $this->assertEquals($sharing->getBuyerMemberId(), getenv('BUYER_ID'));
    }

    /**
     * @test
     */
    public function findById_will_return_correnct_member_data_sharing()
    {
        $mdRepo = $this->getMemberDataSharingRepository();

        /** @var MemberDataSharing $sharing */
        $sharings = $mdRepo->findById(getenv('MEMBER_DATA_SHARING_RECORD_ID'));

        $this->assertCount(1, $sharings);

        $sharing = $sharings[0];

        $this->assertInstanceOf(MemberDataSharing::class, $sharing);

        $this->assertEquals($sharing->getDataMemberId(), getenv('MEMBER_ID'));
        $this->assertEquals($sharing->getId(), getenv('MEMBER_DATA_SHARING_RECORD_ID'));
    }

    /**
     * @test
     */
    public function update_will_change_the_sharing_record()
    {
        $repository = $this->getMemberDataSharingRepository();

        /** @var MemberDataSharing $sharing */
        $sharings = $repository->findById(getenv('MEMBER_DATA_SHARING_RECORD_ID'));

        $this->assertCount(1, $sharings);

        $sharing = $sharings[0];

        $this->assertInstanceOf(MemberDataSharing::class, $sharing);

        $this->assertEquals($sharing->getDataMemberId(), getenv('MEMBER_ID'));
        $this->assertEquals($sharing->getId(), getenv('MEMBER_DATA_SHARING_RECORD_ID'));

        $sharing->setSegmentExposure('all');
        $sharing->setSegments([]);
        $repositoryResponse = $repository->update($sharing);
        $this->assertTrue($repositoryResponse->isSuccessful(), (string)$repositoryResponse->getError()->getError());
        $sharings = $repository->findById(getenv('MEMBER_DATA_SHARING_RECORD_ID'));
        $sharing  = $sharings[0];
        $this->assertEquals($sharing->getSegmentExposure(), 'all');

        $sharing->setSegmentExposure('list');
        $segment = new MemberDataSharingSegment();
        $segment->setId(getenv('SEGMENT_ID'));
        $sharing->addSegments($segment);
        $repositoryResponse = $repository->update($sharing);
        $this->assertTrue($repositoryResponse->isSuccessful(), (string)$repositoryResponse->getError()->getError());
        $sharings = $repository->findById(getenv('MEMBER_DATA_SHARING_RECORD_ID'));
        $sharing  = $sharings[0];
        $this->assertEquals($sharing->getSegmentExposure(), 'list');
    }

}
