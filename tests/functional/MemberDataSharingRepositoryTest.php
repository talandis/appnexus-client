<?php

namespace Test\functional;

use Audiens\AppnexusClient\entity\MemberDataSharing;
use Audiens\AppnexusClient\entity\MemberDataSharingSegment;
use Test\FunctionalTestCase;

/**
 * Class MemberDataSharingRepositoryTest
 */
class MemberDataSharingRepositoryTest extends FunctionalTestCase
{
    /**
     * @test
     */
    public function find_all_will_return_multiple_member_data_sharing()
    {

        $mdSharings = $this
            ->getMemberDataSharingRepository()
            ->findAll();

        foreach ($mdSharings as $sharing) {
            $this->assertInstanceOf(MemberDataSharing::class, $sharing);
        }
    }

    /**
     * @test
     */
    public function findByBuyerId_will_return_correnct_member_data_sharing()
    {
        $this->markTestSkipped('Please insert a valid buyerId');

        $buyerId = ''; // please insert a valid buyerId
        $mdRepo = $this->getMemberDataSharingRepository();

        /** @var MemberDataSharing $sharing */
        $sharings = $mdRepo->findByBuyerId($buyerId, getenv('MEMBER_ID'));

        $this->assertCount(1, $sharings);

        $sharing = $sharings[0];

        $this->assertInstanceOf(MemberDataSharing::class, $sharing);

        $this->assertEquals($sharing->getDataMemberId(), getenv('MEMBER_ID'));
        $this->assertEquals($sharing->getBuyerMemberId(), $buyerId);
    }

    /**
     * @test
     */
    public function findById_will_return_correnct_member_data_sharing()
    {
        $this->markTestSkipped('Please insert a valid record id');

        $id = ''; //please insert a valid record id

        $mdRepo = $this->getMemberDataSharingRepository();

        /** @var MemberDataSharing $sharing */
        $sharings = $mdRepo->findById($id);

        $this->assertCount(1, $sharings);

        $sharing = $sharings[0];

        $this->assertInstanceOf(MemberDataSharing::class, $sharing);

        $this->assertEquals($sharing->getDataMemberId(), getenv('MEMBER_ID'));
        $this->assertEquals($sharing->getId(), $id);
    }

    /**
     * @test
     */
    public function add_will_add_the_member_data_sharing()
    {
        $this->markTestSkipped('Please insert a valid buyerId');

        $buyerId = ''; // please insert a valid buyerId

        $mdSharing = new MemberDataSharing();

        $mdSharing->setBuyerMemberId($buyerId);
        $mdSharing->setDataMemberId(getenv('MEMBER_ID'));
        $mdSharing->setSegmentExposure(MemberDataSharing::SEGMENT_EXPOSURE_ALL);

        $mdRepo = $this->getMemberDataSharingRepository();

        $repositoryResponse = $mdRepo->add($mdSharing);

        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());
    }

    /**
     * @test
     */
    public function addSegmentsToMemberDataSharing_will_add_the_new_segments_to_the_member_data_sharing()
    {
        $this->markTestSkipped('Please insert a valid buyerId');

        $buyerId = ''; // please insert a valid buyerId
        $oldSegmentId = ''; // please insert a valid segment id
        $newSegmentId = '';// please insert a valid segment id
        $segment = new MemberDataSharingSegment();

        $segment->setId($oldSegmentId);

        $mdSharing = new MemberDataSharing();

        $mdSharing->setBuyerMemberId($buyerId);
        $mdSharing->setDataMemberId(getenv('MEMBER_ID'));
        $mdSharing->setSegmentExposure(MemberDataSharing::SEGMENT_EXPOSURE_LIST);
        $mdSharing->addSegments($segment);
        $mdRepo = $this->getMemberDataSharingRepository();

        $repositoryResponse = $mdRepo->add($mdSharing);

        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());

        $mdRepo = $this->getMemberDataSharingRepository();

        $segmentNew = new MemberDataSharingSegment();
        $segmentNew->setId($newSegmentId);
        $segmentNew->setName('test2');

        $repositoryResponse = $mdRepo->addSegmentsToMemberDataSharing($mdSharing->getId(), [$segmentNew]);

        $this->assertTrue($repositoryResponse->isSuccessful(), $repositoryResponse->getError()->getError());

        /** @var MemberDataSharing $sharing */
        $sharings = $mdRepo->findById($mdSharing->getId());

        $this->assertNotEmpty($sharings);
        $this->assertEquals(1, count($sharings));

        $sharing = $sharings[0];

        $this->assertInstanceOf(MemberDataSharing::class, $sharing);

        $segmentsOnline = $sharing->getSegments();

        $this->assertCount(2, $segmentsOnline);

        $found = false;

        foreach($sharing->getSegments() as $mdObj)
        {
            if($mdObj->getId() === $newSegmentId)
            {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }
}