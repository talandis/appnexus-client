<?php

namespace Test;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\entity\Segment;
use Audiens\AppnexusClient\repository\SegmentRepository;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Prophecy\Argument;

/**
 * Class SegmentRepositoryTest
 */
class SegmentRepositoryTest extends TestCase
{

    /**
     * @test
     */
    public function add_will_create_a_new_segment_and_return_a_repository_response()
    {

        $client = $this->prophesize(Auth::class);

        $repository = new SegmentRepository($client->reveal());

        $segment = new Segment();
        $segment->setName('Test segment');

        // ID 5012
        $responseBody = json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'segment' => [
                        'id' => 5012,
                    ],
                ],
            ]
        );

        $fakeResponse = $this->prophesize(Response::class);
        $stream = $this->prophesize(Stream::class);
        $stream->getContents()->willReturn($responseBody);
        $stream->rewind()->willReturn(null)->shouldBeCalled();
        $fakeResponse->getBody()->willReturn($stream->reveal());

        $payload = [
            'segment' => [
                'active' => $segment->isActive(),
                'description' => $segment->getDescription(),
                'member_id' => $segment->getMemberId(),
                'code' => $segment->getCode(),
                'provider' => $segment->getProvider(),
                'price' => $segment->getPrice(),
                'short_name' => $segment->getName(),
                'expire_minutes' => $segment->getExpireMinutes(),
                'category' => $segment->getCategory(),
                'last_activity' => $segment->getLastActivity()->getTimestamp(),
                'enable_rm_piggyback' => $segment->isEnableRmPiggyback(),
            ],
        ];

        $client->request('POST', Argument::any(), ['body' => json_encode($payload)])
               ->willReturn($fakeResponse)
               ->shouldBeCalled();

        $repositoryResponse = $repository->add($segment);

        $this->assertTrue($repositoryResponse->isSuccessful());
        $this->assertEquals(5012, $segment->getId());

    }

}
