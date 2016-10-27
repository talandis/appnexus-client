<?php

namespace Audiens\AppnexusClient\facade;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\authentication\AdnxStrategy;
use Audiens\AppnexusClient\CacheableInterface;
use Audiens\AppnexusClient\entity\Segment;
use Audiens\AppnexusClient\entity\UploadJobStatus;
use Audiens\AppnexusClient\entity\UploadTicket;
use Audiens\AppnexusClient\repository\RepositoryResponse;
use Audiens\AppnexusClient\repository\SegmentRepository;
use Audiens\AppnexusClient\service\Report;
use Audiens\AppnexusClient\service\UserUpload;
use Doctrine\Common\Cache\FilesystemCache;
use GuzzleHttp\Client;

/**
 * Class AppnexusFacade
 */
class AppnexusFacade implements CacheableInterface
{

    protected $username;
    protected $password;
    protected $memberId;

    /** @var  SegmentRepository */
    private $segmentRepository;

    /** @var UserUpload */
    private $userUpload;

    /** @var Report */
    private $report;

    /**
     * AppnexusFacade constructor.
     *
     * @param $username
     * @param $password
     * @param $memberId
     */
    public function __construct($username, $password, $memberId)
    {
        $this->username = $username;
        $this->password = $password;
        $this->memberId = $memberId;

        $client = new Client();
        $cache = new FilesystemCache('build');

        $authStrategy = new AdnxStrategy($client, $cache);

        $auth = new Auth($username, $password, $client, $authStrategy);

        $this->segmentRepository = new SegmentRepository($auth, $cache);
        $this->userUpload = new UserUpload($auth, $cache);
        $this->report = new Report($auth, $cache);
    }

    /**
     * @param Segment $segment
     *
     * @return RepositoryResponse
     * @throws \Exception
     */
    public function add(Segment $segment)
    {

        return $this->segmentRepository->add($segment);
    }

    /**
     * @param $id
     *
     * @return RepositoryResponse
     */
    public function remove($id)
    {

        return $this->segmentRepository->remove($this->memberId, $id);
    }

    /**
     * @param Segment $segment
     *
     * @return RepositoryResponse
     * @throws \Exception
     */
    public function update(Segment $segment)
    {
        return $this->segmentRepository->update($segment);
    }

    /**
     * @param $id
     *
     * @return Segment|null
     */
    public function findOneById($id)
    {
        return $this->segmentRepository->findOneById($this->memberId, $id);
    }

    /**
     * @param int $start
     * @param int $maxResults
     *
     * @return array|mixed|null
     * @throws \Exception
     */
    public function findAll($start = 0, $maxResults = 100)
    {

        return $this->segmentRepository->findAll($this->memberId, $start, $maxResults);
    }

    /**
     * @param $fileAsString
     *
     * @return \Audiens\AppnexusClient\entity\UploadJobStatus
     * @throws \Exception
     */
    public function upload($fileAsString)
    {
        return $this->userUpload->upload($this->memberId, $fileAsString);
    }

    /**
     * @param int $start
     * @param int $maxResults
     *
     * @return \Audiens\AppnexusClient\entity\UploadJobStatus[]
     * @throws \Audiens\AppnexusClient\exceptions\RepositoryException
     */
    public function getUploadHistory($start = 0, $maxResults = 100)
    {
        return $this->userUpload->getUploadHistory($this->memberId, $start, $maxResults);
    }

    /**
     * @return \Audiens\AppnexusClient\entity\UploadTicket
     * @throws \Exception
     */
    public function getUploadTicket()
    {
        return $this->userUpload->getUploadTicket($this->memberId);
    }

    /**
     * @param UploadTicket $uploadTicket
     *
     * @return UploadJobStatus $uploadJobStatus
     * @throws \Exception
     */
    public function getJobStatus(UploadTicket $uploadTicket)
    {
        return $this->userUpload->getJobStatus($uploadTicket);
    }

    /**
     * @param array $reportFormat
     *
     * @return array
     * @throws \Audiens\AppnexusClient\exceptions\RepositoryException
     */
    public function getReport($reportFormat = Report::REVENUE_REPORT)
    {

        $reportStatus = $this->report->getReportStatus($this->report->getReportTicket($reportFormat));

        $maxSteps = 0;

        while (!$reportStatus->isReady() && $maxSteps < 10) {
            $reportStatus = $this->report->getReportStatus($reportStatus);
            $maxSteps++;
        }

        return $this->report->getReport($reportStatus);
    }

    /**
     * @return bool
     */
    public function isCacheEnabled()
    {
        return $this->segmentRepository->isCacheEnabled() || $this->userUpload->isCacheEnabled();
    }

    public function disableCache()
    {
        $this->segmentRepository->disableCache();
        $this->userUpload->disableCache();
    }

    public function enableCache()
    {
        $this->segmentRepository->enableCache();
        $this->userUpload->enableCache();
    }
}
