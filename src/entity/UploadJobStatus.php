<?php

namespace Audiens\AppnexusClient\entity;

class UploadJobStatus extends UploadTicket
{

    public const PHASE_COMPLETED = 'completed';

    use HydratableTrait;

    protected $phase;
    protected $start_time;
    protected $uploaded_time;
    protected $validated_time;
    protected $completed_time;
    protected $error_code;
    protected $time_to_process;
    protected $percent_complete;
    protected $num_valid;
    protected $num_valid_user;
    protected $num_invalid_format;
    protected $num_invalid_user;
    protected $num_invalid_segment;
    protected $num_unauth_segment;
    protected $num_past_expiration;
    protected $num_inactive_segment;
    protected $num_other_error;
    protected $error_log_lines;
    protected $segment_log_lines;
    protected $created_on;

    public function getPhase()
    {
        return $this->phase;
    }

    public function setPhase($phase)
    {
        $this->phase = $phase;
    }

    public function getStartTime()
    {
        return $this->start_time;
    }

    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }

    public function getUploadedTime()
    {
        return $this->uploaded_time;
    }

    public function setUploadedTime($uploaded_time)
    {
        $this->uploaded_time = $uploaded_time;
    }

    public function getValidatedTime()
    {
        return $this->validated_time;
    }

    public function setValidatedTime($validated_time)
    {
        $this->validated_time = $validated_time;
    }

    public function getCompletedTime()
    {
        return $this->completed_time;
    }

    public function setCompletedTime($completed_time)
    {
        $this->completed_time = $completed_time;
    }

    public function getErrorCode()
    {
        return $this->error_code;
    }

    public function setErrorCode($error_code)
    {
        $this->error_code = $error_code;
    }

    public function getTimeToProcess()
    {
        return $this->time_to_process;
    }

    public function setTimeToProcess($time_to_process)
    {
        $this->time_to_process = $time_to_process;
    }

    public function getPercentComplete()
    {
        return $this->percent_complete;
    }

    public function setPercentComplete($percent_complete)
    {
        $this->percent_complete = $percent_complete;
    }

    public function getNumValid()
    {
        return $this->num_valid;
    }

    public function setNumValid($num_valid)
    {
        $this->num_valid = $num_valid;
    }

    public function getNumValidUser()
    {
        return $this->num_valid_user;
    }

    public function setNumValidUser($num_valid_user)
    {
        $this->num_valid_user = $num_valid_user;
    }

    public function getNumInvalidFormat()
    {
        return $this->num_invalid_format;
    }

    public function setNumInvalidFormat($num_invalid_format)
    {
        $this->num_invalid_format = $num_invalid_format;
    }

    public function getNumInvalidUser()
    {
        return $this->num_invalid_user;
    }

    public function setNumInvalidUser($num_invalid_user)
    {
        $this->num_invalid_user = $num_invalid_user;
    }

    public function getNumInvalidSegment()
    {
        return $this->num_invalid_segment;
    }

    public function setNumInvalidSegment($num_invalid_segment)
    {
        $this->num_invalid_segment = $num_invalid_segment;
    }

    public function getNumUnauthSegment()
    {
        return $this->num_unauth_segment;
    }

    public function setNumUnauthSegment($num_unauth_segment)
    {
        $this->num_unauth_segment = $num_unauth_segment;
    }

    public function getNumPastExpiration()
    {
        return $this->num_past_expiration;
    }

    public function setNumPastExpiration($num_past_expiration)
    {
        $this->num_past_expiration = $num_past_expiration;
    }

    public function getNumInactiveSegment()
    {
        return $this->num_inactive_segment;
    }

    public function setNumInactiveSegment($num_inactive_segment)
    {
        $this->num_inactive_segment = $num_inactive_segment;
    }

    public function getNumOtherError()
    {
        return $this->num_other_error;
    }

    public function setNumOtherError($num_other_error)
    {
        $this->num_other_error = $num_other_error;
    }

    public function getErrorLogLines()
    {
        return $this->error_log_lines;
    }

    public function setErrorLogLines($error_log_lines)
    {
        $this->error_log_lines = $error_log_lines;
    }

    public function getSegmentLogLines()
    {
        return $this->segment_log_lines;
    }

    public function setSegmentLogLines($segment_log_lines)
    {
        $this->segment_log_lines = $segment_log_lines;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getJobId()
    {
        return $this->job_id;
    }

    public function setJobId($job_id)
    {
        $this->job_id = $job_id;
    }

    public function getMemberId()
    {
        return $this->member_id;
    }

    public function setMemberId($member_id)
    {
        $this->member_id = $member_id;
    }

    public function getCreatedOn()
    {
        return $this->created_on;
    }

    public function setCreatedOn($created_on)
    {
        $this->created_on = $created_on;
    }

    public function getLastModified()
    {
        return $this->last_modified;
    }

    public function setLastModified($last_modified)
    {
        $this->last_modified = $last_modified;
    }

    public function isCompeted(): bool
    {
        return $this->phase == self::PHASE_COMPLETED;
    }

    public static function fromArray(array $objectArray)
    {
        $object = new self();

        if (!isset($objectArray['upload_url'])) {
            $objectArray['upload_url'] = '';
        }

        self::getHydrator()->hydrate($objectArray, $object);

        return $object;
    }
}
