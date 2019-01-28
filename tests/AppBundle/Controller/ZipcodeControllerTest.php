<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class ZipcodeControllerTest extends AbstractControllerTest
{
    public function setUp()
    {
        parent::setUp();
        $this->loadZipcodeFixtures();
    }

    public function testGetAllZipcodes()
    {
        $expected = file_get_contents('tests/Fixtures/zipcodes.json');

        $this->client->request('GET', '/zipcode');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $this->client->getResponse()->getContent());
    }

    public function testGetOneZipcodeFound()
    {
        $expected = '{"id":"01623","city":"Lommatzsch"}';

        $this->client->request('GET', '/zipcode/01623');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $this->client->getResponse()->getContent());
    }

    public function testGetOneZipcodeNotFound()
    {
        $this->client->request('GET', '/zipcode/1');

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testPostZipcodeRepeatedReturnsBadRequest()
    {
        $this->client->request(
            'POST',
            '/zipcode',
            [],
            [],
            ['CONTENT-TYPE' => 'application/json'],
            '{"id": "01623", "city": "Lommatzsch"}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testPostInvalidZipcodeReturnsBadRequest()
    {
        $this->client->request(
            'POST',
            '/zipcode',
            [],
            [],
            ['CONTENT-TYPE' => 'application/json'],
            '{"id": "123", "city": ""}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testPostValidZipcodeReturnsCreated()
    {
        $this->client->request(
            'POST',
            '/zipcode',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"id": "12345", "city": "Valid city"}'
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }
}
