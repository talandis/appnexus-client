<?php

namespace Audiens\AppnexusClient\repository;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\entity\UserSegment;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class UserSegmentRepository
 */
class UserSegmentRepository
{

    const BASE_URL = 'http://api.appnexus.com/batch-segment';
//    const BASE_URL_ADNXS = 'http://sand.api.appnexus.com/batch-segment';

    /** @var  \SplQueue */
    protected $userSegments;

    /** @var Client|Auth */
    protected $client;

    /** @var  int */
    protected $memberId;

    /** @var  Cache */
    protected $cache;

    /** @var bool */
    protected $cacheEnabled;

    const CACHE_NAMESPACE = 'appnexus_segment_user_upload';

    const CACHE_EXPIRATION = 3600;

    /**
     * SegmentRepository constructor.
     *
     * @param ClientInterface $client
     * @param Cache|null      $cache
     */
    public function __construct(ClientInterface $client, Cache $cache = null)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->cacheEnabled = $cache instanceof Cache;

    }


    /**
     * @param $fileAsString
     * @param $memberId
     *
     * @return RepositoryResponse
     * @throws \Exception
     */
    public function upload($fileAsString,$memberId){

        $tempFile = tmpfile();

        fwrite($tempFile, $fileAsString);
        fseek($tempFile, 0);


        $url = $this->getUploadUrl($memberId);

        $contentPayload = [
            # Content-Disposition: form-data; name="file"; filename="/the/full/path/of/image/file/you/want/to/upload.png"
            'Content-Disposition' => sprintf('form-data; name="%s"; filename="%s"', 'file', $myFile),
            # Content-Type: application/octet-stream
            # 'Content-Type' => 'application/octet-stream'
        ];

        $response = $this->client->request('PUT', $url, ['body' => $tempFile]);

        $stream = $response->getBody();

        $responseContent = json_decode($stream->getContents(), true);


        print_r($responseContent);die();




        fclose($tempFile); // this removes the file


    }



    /**
     * @param $memberId
     *
     * @return string
     * @throws \Exception
     */
    public function getUploadUrl($memberId)
    {

        $compiledUrl = self::BASE_URL.'?member_id='.$memberId;

        $response = $this->client->request('POST', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw new \Exception('name me');
        }

        if (!isset($responseContent['response']['batch_segment_upload_job']['upload_url'])) {
            throw new \Exception('name me');
        }

        $stream = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        return $responseContent['response']['batch_segment_upload_job']['upload_url'];

    }


    /**
     * @return boolean
     */
    public function isCacheEnabled()
    {
        return $this->cacheEnabled;
    }

    public function disableCache()
    {
        $this->cacheEnabled = false;
    }

    public function enableCache()
    {
        $this->cacheEnabled = true;
    }

}
