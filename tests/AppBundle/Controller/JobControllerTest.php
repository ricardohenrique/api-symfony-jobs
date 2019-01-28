<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class JobControllerTest extends AbstractControllerTest
{
    /**
     * @var array
     */
    private $defaultJob;

    public function setUp()
    {
        parent::setUp();
        $this->loadServiceFixtures();
        $this->loadZipcodeFixtures();
        $this->loadJobFixtures();
        $this->defaultJob = [
            'serviceId' => 804040,
            'zipcodeId' => '10115',
            'title' => 'title',
            'description' => 'decription',
            'dateToBeDone' => '2018-11-11'
        ];
    }

    public function testGetAllJobs()
    {
        $this->client->request('GET', '/job');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testGetOneJobFound()
    {

    }

    public function testGetOneJobNotFound()
    {

    }

    public function testPostInvalidJobReturnsBadRequest()
    {
        $this->defaultJob['title'] = '';

        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testPostJobWithServiceNotFoundReturnsBadRequest()
    {
        $this->defaultJob['serviceId'] = 12345;

        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testPostJobWithZipcodeNotFoundReturnsBadRequest()
    {
        $this->defaultJob['zipcodeId'] = '12345';

        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testPostValidJobNewJobIsCreated()
    {
        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testPutWithNotFoundJobReturnsNotFound()
    {
        $this->client->request(
            'PUT',
            '/job/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testPutWithValidJobReturnsNotFound()
    {
        $id = $this->getFirstJobId();
        $this->client->request(
            'PUT',
            '/job/' . $id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return String
     */
    private function getFirstJobId()
    {
        $this->client->request('GET', '/job');
        $allJobs = json_decode($this->client->getResponse()->getContent(), true);
        $id = $allJobs[0]['id'];

        return $id;
    }
}
