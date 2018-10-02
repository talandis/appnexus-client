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

class AppnexusFacade implements CacheableInterface
{

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    /** @var int */
    protected $memberId;

    /** @var  SegmentRepository */
    private $segmentRepository;

    /** @var UserUpload */
    private $userUpload;

    /** @var Report */
    private $report;

    public function __construct(string $username, string $password, int $memberId)
    {
        $this->username = $username;
        $this->password = $password;
        $this->memberId = $memberId;

        $client = new Client();
        $cache  = new FilesystemCache('build');

        $authStrategy = new AdnxStrategy($client, $cache);

        $auth = new Auth($username, $password, $client, $authStrategy);

        $this->segmentRepository = new SegmentRepository($auth, $cache, $memberId);
        $this->userUpload        = new UserUpload($auth, $cache);
        $this->report            = new Report($auth, $cache);
    }

    public function add(Segment $segment)
    {
        return $this->segmentRepository->add($segment);
    }

    public function remove($id)
    {
        return $this->segmentRepository->remove($id);
    }

    public function update(Segment $segment): RepositoryResponse
    {
        return $this->segmentRepository->update($segment);
    }

    public function findOneById($id): ?Segment
    {
        return $this->segmentRepository->findOneById($id);
    }

    public function findAll(int $start = 0, int $maxResults = 100)
    {
        return $this->segmentRepository->findAll($start, $maxResults);
    }

    public function upload(string $fileAsString): UploadJobStatus
    {
        return $this->userUpload->upload($this->memberId, $fileAsString);
    }

    public function getUploadHistory(int $start = 0, int $maxResults = 100): array
    {
        return $this->userUpload->getUploadHistory($this->memberId, $start, $maxResults);
    }

    public function getUploadTicket(): UploadTicket
    {
        return $this->userUpload->getUploadTicket($this->memberId);
    }

    public function getJobStatus(UploadTicket $uploadTicket): UploadJobStatus
    {
        return $this->userUpload->getJobStatus($uploadTicket);
    }

    public function getReport($reportFormat = Report::REVENUE_REPORT): array
    {
        $reportStatus = $this->report->getReportStatus($this->report->getReportTicket($reportFormat));

        $maxSteps = 0;

        while (!$reportStatus->isReady() && $maxSteps < 10) {
            $reportStatus = $this->report->getReportStatus($reportStatus);
            $maxSteps++;
        }

        return $this->report->getReport($reportStatus);
    }

    public function isCacheEnabled(): bool
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
