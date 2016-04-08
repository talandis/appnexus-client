<?php

namespace Test;

use Audiens\AppnexusClient\entity\UploadJobStatus;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Prophecy\Argument;

/**
 * Class SegmentRepositoryFunctionTest
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @param $responseBody
     *
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    protected function getFakeResponse($responseBody)
    {

        $fakeResponse = $this->prophesize(Response::class);
        $stream = $this->prophesize(Stream::class);
        $stream->getContents()->willReturn($responseBody);
        $stream->rewind()->willReturn(null)->shouldBeCalled();
        $fakeResponse->getBody()->willReturn($stream->reveal());

        return $fakeResponse;

    }

    /**
     * @return string
     */
    protected function getCompletedJob()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'batch_segment_upload_job' => [
                        [
                            'phase' => UploadJobStatus::PHASE_COMPLETED,
                            "start_time" => '2016-04-08 08:41:16',
                            "uploaded_time" => '2016-04-08 08:41:17',
                            "validated_time" => '2016-04-08 08:41:19',
                            "completed_time" => '2016-04-08 08:41:19',
                            "error_code" => null,
                            "time_to_process" => 0.01,
                            "percent_complete" => 100,
                            "num_valid" => 88529,
                            "num_valid_user" => 0,
                            "num_invalid_format" => 0,
                            "num_invalid_user" => 0,
                            "num_invalid_segment" => 0,
                            "num_unauth_segment" => 0,
                            "num_past_expiration" => 0,
                            "num_inactive_segment" => 0,
                            "num_other_error" => 0,
                            "error_log_lines" => null,
                            "segment_log_lines" => null,
                            "created_on" => '2016-04-08 08:41:15',
                            "id" => 25450408,
                            "job_id" => 'iAMaO5SVpsdt42RIU7nIj4GXejfX1n1460104875',
                            "member_id" => 3847,
                            "last_modified" => '2016-04-08 08:41:19',
                            "upload_url" => null,
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getUploadTicket()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'batch_segment_upload_job' => [
                        "id" => 25450408,
                        "job_id" => 'iAMaO5SVpsdt42RIU7nIj4GXejfX1n1460104875',
                        "member_id" => 3847,
                        "last_modified" => '2016-04-08 08:41:19',
                        "upload_url" => 'upload_here',
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getSingleSegment()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'segment' => [
                        "id" => 5012,
                        "active" => true,
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getMultipleSegments()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'segments' => [
                        [
                            "id" => 123,
                            "active" => true,
                        ],
                        [
                            "id" => 456,
                            "active" => true,
                        ],
                        [
                            "id" => 789,
                            "active" => true,
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @return string
     */
    protected function getFailedResponse()
    {
        return json_encode(
            [
                'response' =>
                    [
                        'error_id' => 'SYNTAX',
                        'error' => 'Invalid path /segment - member is required',
                        'error_description' => null,
                        'error_code' => null,
                        'service' => 'segment',
                        'method' => 'POST',
                    ],
            ]

        );
    }

    /**
     * @return string
     */
    protected function getUploadHystory()
    {
        return json_encode(
            [
                'response' => [
                    'status' => 'OK',
                    'batch_segment_upload_job' => [
                        [
                            'phase' => UploadJobStatus::PHASE_COMPLETED,
                            "start_time" => '2016-04-08 08:41:16',
                            "uploaded_time" => '2016-04-08 08:41:17',
                            "validated_time" => '2016-04-08 08:41:19',
                            "completed_time" => '2016-04-08 08:41:19',
                            "error_code" => null,
                            "time_to_process" => 0.01,
                            "percent_complete" => 100,
                            "num_valid" => 88529,
                            "num_valid_user" => 0,
                            "num_invalid_format" => 0,
                            "num_invalid_user" => 0,
                            "num_invalid_segment" => 0,
                            "num_unauth_segment" => 0,
                            "num_past_expiration" => 0,
                            "num_inactive_segment" => 0,
                            "num_other_error" => 0,
                            "error_log_lines" => null,
                            "segment_log_lines" => null,
                            "created_on" => '2016-04-08 08:41:15',
                            "id" => 25450408,
                            "job_id" => 'iAMaO5SVpsdt42RIU7nIj4GXejfX1n1460104875',
                            "member_id" => 3847,
                            "last_modified" => '2016-04-08 08:41:19',
                            "upload_url" => null,
                        ],
                        [
                            'phase' => UploadJobStatus::PHASE_COMPLETED,
                            "start_time" => '2016-04-08 08:41:16',
                            "uploaded_time" => '2016-04-08 08:41:17',
                            "validated_time" => '2016-04-08 08:41:19',
                            "completed_time" => '2016-04-08 08:41:19',
                            "error_code" => null,
                            "time_to_process" => 0.01,
                            "percent_complete" => 100,
                            "num_valid" => 88529,
                            "num_valid_user" => 0,
                            "num_invalid_format" => 0,
                            "num_invalid_user" => 0,
                            "num_invalid_segment" => 0,
                            "num_unauth_segment" => 0,
                            "num_past_expiration" => 0,
                            "num_inactive_segment" => 0,
                            "num_other_error" => 0,
                            "error_log_lines" => null,
                            "segment_log_lines" => null,
                            "created_on" => '2016-04-08 08:41:15',
                            "id" => 25450408,
                            "job_id" => 'iAMaO5SVpsdt42RIU7nIj4GXejfX1n1460104875',
                            "member_id" => 3847,
                            "last_modified" => '2016-04-08 08:41:19',
                            "upload_url" => null,
                        ],
                        [
                            'phase' => UploadJobStatus::PHASE_COMPLETED,
                            "start_time" => '2016-04-08 08:41:16',
                            "uploaded_time" => '2016-04-08 08:41:17',
                            "validated_time" => '2016-04-08 08:41:19',
                            "completed_time" => '2016-04-08 08:41:19',
                            "error_code" => null,
                            "time_to_process" => 0.01,
                            "percent_complete" => 100,
                            "num_valid" => 88529,
                            "num_valid_user" => 0,
                            "num_invalid_format" => 0,
                            "num_invalid_user" => 0,
                            "num_invalid_segment" => 0,
                            "num_unauth_segment" => 0,
                            "num_past_expiration" => 0,
                            "num_inactive_segment" => 0,
                            "num_other_error" => 0,
                            "error_log_lines" => null,
                            "segment_log_lines" => null,
                            "created_on" => '2016-04-08 08:41:15',
                            "id" => 25450408,
                            "job_id" => 'iAMaO5SVpsdt42RIU7nIj4GXejfX1n1460104875',
                            "member_id" => 3847,
                            "last_modified" => '2016-04-08 08:41:19',
                            "upload_url" => null,
                        ],
                    ],
                ],
            ]
        );
    }


}
