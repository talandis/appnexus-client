<?php

namespace Test\functional;

use Test\FunctionalTestCase;

class UserUploadTest extends FunctionalTestCase
{

    /**
     * @test
     */
    public function get_ticket_will_return_an_object()
    {
        $this->markTestSkipped();
        $service = $this->getUserUpload();

        $job = $service->getUploadTicket(getenv('MEMBER_ID'));

        $this->assertNotNull($job->getId());
        $this->assertNotNull($job->getJobId());
        $this->assertNotNull($job->getUploadUrl());
    }

    /**
     * @test
     */
    public function upload_will_return_a_job_status()
    {
        $this->markTestSkipped();
        $service = $this->getUserUpload();

        $fileAsString = "5727816213491965430,78610639;'it.gender.male';7776000;1458191702;0;0\n";
        $jobStatus    = $service->upload(getenv('MEMBER_ID'), $fileAsString);

        $this->assertNotNull($jobStatus->getId());
        $this->assertNotNull($jobStatus->getPhase());
        $this->assertNotNull($jobStatus->getJobId());
    }

}
