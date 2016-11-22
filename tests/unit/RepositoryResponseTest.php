<?php

namespace Test\unit;

use Audiens\AppnexusClient\entity\Error;
use Audiens\AppnexusClient\repository\RepositoryResponse;
use GuzzleHttp\Psr7\Response;
use Prophecy\Argument;
use Test\TestCase;

/**
 * Class RepositoryResponseTest
 */
class RepositoryResponseTest extends TestCase
{

    /**
     * @test
     * @dataProvider wrongResponseProvider
     */
    public function it_should_report_a_failure_if_the_response_is_not_ok(Response $response)
    {

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        $this->assertFalse($repositoryResponse->isSuccessful());

        $this->assertNotEmpty($repositoryResponse->getError()->getError(), 'error');
        $this->assertNotEmpty($repositoryResponse->getError()->getErrorId(), 'error id');

    }

    /**
     * @test
     */
    public function it_should_report_a_success_if_the_response_is_ok()
    {

        $repositoryResponse = RepositoryResponse::fromResponse($this->getOKResponse());

        $this->assertTrue($repositoryResponse->isSuccessful());

    }

    /**
     * @test
     */
    public function always_contains_an_error()
    {

        $repositoryResponse = RepositoryResponse::fromResponse($this->getOKResponse());

        $this->assertInstanceOf(Error::class, $repositoryResponse->getError());

    }

    /**
     * @return Response
     */
    public function wrongResponseProvider()
    {

        return [
            [
                new Response(
                    200,
                    [],
                    '{"response":{"error_id":"SYNTAX","error":"Invalid path \/segment - member is required","error_description":null,"error_code":null,"service":"segment","method":"POST","dbg":{"instance":"40.api.prod.ams1","slave_hit":false,"db":"master","user::reads":0,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":1,"user::write_limit":60,"user::write_limit_seconds":60,"reads":0,"read_limit":1073741824,"read_limit_seconds":60,"writes":1,"write_limit":1073741824,"write_limit_seconds":60,"parent_dbg_info":{"instance":"44.bm-api.prod.nym2","slave_hit":false,"db":"master","user::reads":0,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":1,"user::write_limit":60,"user::write_limit_seconds":60,"reads":0,"read_limit":1073741824,"read_limit_seconds":60,"writes":1,"write_limit":1073741824,"write_limit_seconds":60,"time":48.772096633911,"version":"1.16.497","warnings":[],"slave_lag":0,"start_microtime":1458732144.0725},"time":278.25713157654,"version":"1.16.497","warnings":[],"slave_lag":0,"start_microtime":1458732143.8902}}}'
                ),
            ],
            [
                new Response(
                    200,
                    [],
                    '{"response":{"error_id":"SYNTAX","error":"Invalid input for field custom filter which expects type int","error_description":null,"error_code":"CAST_ERROR","service":"batch-segment","method":"GET","dbg":{"instance":"40.api.prod.ams1","slave_hit":true,"db":"10.2.78.203","user::reads":1,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":2,"user::write_limit":60,"user::write_limit_seconds":60,"reads":1,"read_limit":1073741824,"read_limit_seconds":60,"writes":2,"write_limit":1073741824,"write_limit_seconds":60,"time":106.05382919312,"version":"1.16.516","warnings":["Caught user 183216 (member 3847) trying to cast custom filter string to a int in service batch_segment"],"slave_lag":0,"start_microtime":1459766971.6139}}}'
                ),
            ],
        ];

    }

    /**
     * @return Response
     */
    protected function getOKResponse()
    {

//        $responseBody =            '{"response":{"status":"OK","count":0,"start_element":0,"num_elements":0,"dbg_info":{"instance":"02.data-clearing-api.prod.nym2","db":"","warnings":[],"start_microtime":1479746181.609,"time":187,"version":"","output_term":"segment-billing-category"},"segment-billing-category":[]}}';
        $responseBody = '{"response":{"status":"OK","count":1,"start_element":0,"num_elements":100,"id":4959394,"segment":{"id":4959394,"active":true,"description":null,"member_id":3847,"code":null,"provider":"","price":0,"short_name":"Test segment4996","expire_minutes":null,"category":null,"enable_rm_piggyback":false,"last_activity":"2016-03-23 11:21:48","max_usersync_pixels":null,"parent_segment_id":null,"querystring_mapping":null,"querystring_mapping_key_value":null},"dbg":{"instance":"41.api.prod.ams1","slave_hit":false,"db":"master","user::reads":0,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":2,"user::write_limit":60,"user::write_limit_seconds":60,"reads":0,"read_limit":1073741824,"read_limit_seconds":60,"writes":2,"write_limit":1073741824,"write_limit_seconds":60,"parent_dbg_info":{"instance":"44.bm-api.prod.nym2","slave_hit":false,"db":"master","user::reads":0,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":2,"user::write_limit":60,"user::write_limit_seconds":60,"reads":0,"read_limit":1073741824,"read_limit_seconds":60,"writes":2,"write_limit":1073741824,"write_limit_seconds":60,"time":88.721990585327,"version":"1.16.497","warnings":["Field `member_id` is not available"],"slave_lag":0,"start_microtime":1458732108.3462},"time":347.10383415222,"version":"1.16.497","warnings":[],"slave_lag":0,"start_microtime":1458732108.1427}}}';

        return new Response(200, [], $responseBody);

    }

}
