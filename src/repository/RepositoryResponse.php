<?php

namespace Audiens\AppnexusClient\repository;

use Audiens\AppnexusClient\entity\Error;
use GuzzleHttp\Psr7\Response;

/**
 * Class RepositoryResponse
 */
class RepositoryResponse
{

    // OK RESPONSE {"response":{"status":"OK","count":1,"start_element":0,"num_elements":100,"id":4959394,"segment":{"id":4959394,"active":true,"description":null,"member_id":3847,"code":null,"provider":"","price":0,"short_name":"Test segment4996","expire_minutes":null,"category":null,"enable_rm_piggyback":false,"last_activity":"2016-03-23 11:21:48","max_usersync_pixels":null,"parent_segment_id":null,"querystring_mapping":null,"querystring_mapping_key_value":null},"dbg":{"instance":"41.api.prod.ams1","slave_hit":false,"db":"master","user::reads":0,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":2,"user::write_limit":60,"user::write_limit_seconds":60,"reads":0,"read_limit":1073741824,"read_limit_seconds":60,"writes":2,"write_limit":1073741824,"write_limit_seconds":60,"parent_dbg_info":{"instance":"44.bm-api.prod.nym2","slave_hit":false,"db":"master","user::reads":0,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":2,"user::write_limit":60,"user::write_limit_seconds":60,"reads":0,"read_limit":1073741824,"read_limit_seconds":60,"writes":2,"write_limit":1073741824,"write_limit_seconds":60,"time":88.721990585327,"version":"1.16.497","warnings":["Field `member_id` is not available"],"slave_lag":0,"start_microtime":1458732108.3462},"time":347.10383415222,"version":"1.16.497","warnings":[],"slave_lag":0,"start_microtime":1458732108.1427}}}
    // OK DELETE "{"response":{"status":"OK","count":1,"start_element":0,"num_elements":100,"id":"4967144","dbg":{"instance":"41.api.prod.ams1","slave_hit":true,"db":"10.2.78.139","user::reads":1,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":5,"user::write_limit":60,"user::write_limit_seconds":60,"reads":1,"read_limit":1073741824,"read_limit_seconds":60,"writes":5,"write_limit":1073741824,"write_limit_seconds":60,"parent_dbg_info":{"instance":"45.bm-api.prod.nym2","slave_hit":true,"db":"10.3.81.15",
    // KO RESPONSE {"response":{"error_id":"SYNTAX","error":"Invalid path \/segment - member is required","error_description":null,"error_code":null,"service":"segment","method":"POST","dbg":{"instance":"40.api.prod.ams1","slave_hit":false,"db":"master","user::reads":0,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":1,"user::write_limit":60,"user::write_limit_seconds":60,"reads":0,"read_limit":1073741824,"read_limit_seconds":60,"writes":1,"write_limit":1073741824,"write_limit_seconds":60,"parent_dbg_info":{"instance":"44.bm-api.prod.nym2","slave_hit":false,"db":"master","user::reads":0,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":1,"user::write_limit":60,"user::write_limit_seconds":60,"reads":0,"read_limit":1073741824,"read_limit_seconds":60,"writes":1,"write_limit":1073741824,"write_limit_seconds":60,"time":48.772096633911,"version":"1.16.497","warnings":[],"slave_lag":0,"start_microtime":1458732144.0725},"time":278.25713157654,"version":"1.16.497","warnings":[],"slave_lag":0,"start_microtime":1458732143.8902}}}
    // OK RESPONSE {"response":{"status":"OK","count":1,"start_element":0,"num_elements":100,"id":25069653,"batch_segment_upload_job":{"id":25069653,"job_id":"PkIyNufLvcuVMdZ37CqXoonp3KKpjs1459321995","member_id":3847,"last_modified":"2016-03-30 07:13:15","upload_url":"https:\/\/data-api-gslb.adnxs.net\/segment-upload\/PkIyNufLvcuVMdZ37CqXoonp3KKpjs1459321995"},"dbg":{"instance":"40.api.prod.ams1","slave_hit":false,"db":"master","user::reads":0,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":2,"user::write_limit":60,"user::write_limit_seconds":60,"reads":0,"read_limit":1073741824,"read_limit_seconds":60,"writes":2,"write_limit":1073741824,"write_limit_seconds":60,"parent_dbg_info":{"instance":"45.bm-api.prod.nym2","slave_hit":false,"db":"master","user::reads":0,"user::read_limit":100,"user::read_limit_seconds":60,"user::writes":2,"user::write_limit":60,"user::write_limit_seconds":60,"reads":0,"read_limit":1073741824,"read_limit_seconds":60,"writes":2,"write_limit":1073741824,"write_limit_seconds":60,"time":102.07200050354,"version":"1.16.516","warnings":[],"slave_lag":0,"start_microtime":1459321995.0088},"time":323.7988948822,"version":"1.16.516","warnings":[],"slave_lag":1,"start_microtime":1459321994.8377}}}

    const STATUS_SUCCESS = 'OK';

    /** @var bool */
    protected $successful = false;

    /** @var  string */
    protected $response;

    /** @var  Error */
    protected $error;

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getResponseAsArray()
    {
        return json_decode($this->response, true);
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    /**
     * @param boolean $successful
     */
    public function setSuccessful($successful)
    {
        $this->successful = $successful;
    }

    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param Error $error
     */
    public function setError(Error $error)
    {
        $this->error = $error;
    }


    /**
     * @param Response $response
     *
     * @return RepositoryResponse
     */
    public static function fromResponse(Response $response)
    {
        $self = new self();
        $error = new Error();

        $self->setSuccessful(false);

        $responseContent = self::getResponseContent($response);
        $self->setResponse($responseContent);

        $responseArray = json_decode($responseContent, true);



        if (isset($responseArray['response']['status'])) {
            $self->setSuccessful($responseArray['response']['status'] == self::STATUS_SUCCESS);
        }

        if (!$self->isSuccessful()) {
            $error = Error::fromArray($responseArray['response']);
        }

        $self->setError($error);

        return $self;
    }

    /**
     * @param Response $response
     *
     * @return string
     */
    private static function getResponseContent(Response $response)
    {
        $responseContent = $response->getBody()->getContents();
        $response->getBody()->rewind();

        return $responseContent;
    }
}
