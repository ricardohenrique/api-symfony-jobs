<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class ServiceControllerTest extends AbstractControllerTest
{
    public function setUp()
    {
        parent::setUp();
        $this->loadServiceFixtures();
    }

    public function testGetAllServices()
    {
        $expected = file_get_contents('tests/Fixtures/services.json');

        $this->client->request('GET', '/service');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $this->client->getResponse()->getContent());
    }

    public function testGetOneServiceFound()
    {
        $expected = '{"id":411070,"name":"Fensterreinigung"}';

        $this->client->request('GET', '/service/411070');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $this->client->getResponse()->getContent());
    }

    public function testGetOneServiceNotFound()
    {
        $this->client->request('GET', '/service/1');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testPostServiceRepeatedReturnsBadRequest()
    {
        $this->client->request(
            'POST',
            '/service',
            [],
            [],
            ['CONTENT-TYPE' => 'application/json'],
            '{"id": 804040, "name": "Sonstige Umzugsleistungen"}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testPostInvalidServiceReturnsBadRequest()
    {
        $this->client->request(
            'POST',
            '/service',
            [],
            [],
            ['CONTENT-TYPE' => 'application/json'],
            '{"id": 123, "name": ""}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testPostValidServiceReturnsCreated()
    {
        $this->client->request(
            'POST',
            '/service',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"id": 123, "name": "New Service"}'
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }
}
