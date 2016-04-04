<?php

namespace Audiens\AppnexusClient\entity;

/**
 * Class UploadJobStatus
 */
class UploadJobStatus extends UploadTicket
{

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
    protected $id;
    protected $job_id;
    protected $member_id;
    protected $created_on;
    protected $last_modified;

    /**
     * @return mixed
     */
    public function getPhase()
    {
        return $this->phase;
    }

    /**
     * @param mixed $phase
     */
    public function setPhase($phase)
    {
        $this->phase = $phase;
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @param mixed $start_time
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }

    /**
     * @return mixed
     */
    public function getUploadedTime()
    {
        return $this->uploaded_time;
    }

    /**
     * @param mixed $uploaded_time
     */
    public function setUploadedTime($uploaded_time)
    {
        $this->uploaded_time = $uploaded_time;
    }

    /**
     * @return mixed
     */
    public function getValidatedTime()
    {
        return $this->validated_time;
    }

    /**
     * @param mixed $validated_time
     */
    public function setValidatedTime($validated_time)
    {
        $this->validated_time = $validated_time;
    }

    /**
     * @return mixed
     */
    public function getCompletedTime()
    {
        return $this->completed_time;
    }

    /**
     * @param mixed $completed_time
     */
    public function setCompletedTime($completed_time)
    {
        $this->completed_time = $completed_time;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }

    /**
     * @param mixed $error_code
     */
    public function setErrorCode($error_code)
    {
        $this->error_code = $error_code;
    }

    /**
     * @return mixed
     */
    public function getTimeToProcess()
    {
        return $this->time_to_process;
    }

    /**
     * @param mixed $time_to_process
     */
    public function setTimeToProcess($time_to_process)
    {
        $this->time_to_process = $time_to_process;
    }

    /**
     * @return mixed
     */
    public function getPercentComplete()
    {
        return $this->percent_complete;
    }

    /**
     * @param mixed $percent_complete
     */
    public function setPercentComplete($percent_complete)
    {
        $this->percent_complete = $percent_complete;
    }

    /**
     * @return mixed
     */
    public function getNumValid()
    {
        return $this->num_valid;
    }

    /**
     * @param mixed $num_valid
     */
    public function setNumValid($num_valid)
    {
        $this->num_valid = $num_valid;
    }

    /**
     * @return mixed
     */
    public function getNumValidUser()
    {
        return $this->num_valid_user;
    }

    /**
     * @param mixed $num_valid_user
     */
    public function setNumValidUser($num_valid_user)
    {
        $this->num_valid_user = $num_valid_user;
    }

    /**
     * @return mixed
     */
    public function getNumInvalidFormat()
    {
        return $this->num_invalid_format;
    }

    /**
     * @param mixed $num_invalid_format
     */
    public function setNumInvalidFormat($num_invalid_format)
    {
        $this->num_invalid_format = $num_invalid_format;
    }

    /**
     * @return mixed
     */
    public function getNumInvalidUser()
    {
        return $this->num_invalid_user;
    }

    /**
     * @param mixed $num_invalid_user
     */
    public function setNumInvalidUser($num_invalid_user)
    {
        $this->num_invalid_user = $num_invalid_user;
    }

    /**
     * @return mixed
     */
    public function getNumInvalidSegment()
    {
        return $this->num_invalid_segment;
    }

    /**
     * @param mixed $num_invalid_segment
     */
    public function setNumInvalidSegment($num_invalid_segment)
    {
        $this->num_invalid_segment = $num_invalid_segment;
    }

    /**
     * @return mixed
     */
    public function getNumUnauthSegment()
    {
        return $this->num_unauth_segment;
    }

    /**
     * @param mixed $num_unauth_segment
     */
    public function setNumUnauthSegment($num_unauth_segment)
    {
        $this->num_unauth_segment = $num_unauth_segment;
    }

    /**
     * @return mixed
     */
    public function getNumPastExpiration()
    {
        return $this->num_past_expiration;
    }

    /**
     * @param mixed $num_past_expiration
     */
    public function setNumPastExpiration($num_past_expiration)
    {
        $this->num_past_expiration = $num_past_expiration;
    }

    /**
     * @return mixed
     */
    public function getNumInactiveSegment()
    {
        return $this->num_inactive_segment;
    }

    /**
     * @param mixed $num_inactive_segment
     */
    public function setNumInactiveSegment($num_inactive_segment)
    {
        $this->num_inactive_segment = $num_inactive_segment;
    }

    /**
     * @return mixed
     */
    public function getNumOtherError()
    {
        return $this->num_other_error;
    }

    /**
     * @param mixed $num_other_error
     */
    public function setNumOtherError($num_other_error)
    {
        $this->num_other_error = $num_other_error;
    }

    /**
     * @return mixed
     */
    public function getErrorLogLines()
    {
        return $this->error_log_lines;
    }

    /**
     * @param mixed $error_log_lines
     */
    public function setErrorLogLines($error_log_lines)
    {
        $this->error_log_lines = $error_log_lines;
    }

    /**
     * @return mixed
     */
    public function getSegmentLogLines()
    {
        return $this->segment_log_lines;
    }

    /**
     * @param mixed $segment_log_lines
     */
    public function setSegmentLogLines($segment_log_lines)
    {
        $this->segment_log_lines = $segment_log_lines;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getJobId()
    {
        return $this->job_id;
    }

    /**
     * @param mixed $job_id
     */
    public function setJobId($job_id)
    {
        $this->job_id = $job_id;
    }

    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->member_id;
    }

    /**
     * @param mixed $member_id
     */
    public function setMemberId($member_id)
    {
        $this->member_id = $member_id;
    }

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->created_on;
    }

    /**
     * @param mixed $created_on
     */
    public function setCreatedOn($created_on)
    {
        $this->created_on = $created_on;
    }

    /**
     * @return mixed
     */
    public function getLastModified()
    {
        return $this->last_modified;
    }

    /**
     * @param mixed $last_modified
     */
    public function setLastModified($last_modified)
    {
        $this->last_modified = $last_modified;
    }
}
