<?php

namespace AppBundle\Controller;

use AppBundle\Builder\Job as JobBuilder;
use AppBundle\Services\Job;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;

class JobController extends AbstractController
{
    public function __construct()
    {
        $this->serviceName = Job::class;
        $this->builder = JobBuilder::class;
    }

    /**
     * @Rest\Get("/job")
     *
     * @param Request $request
     * @return View
     */
    public function getAllFilteringAction(Request $request): View
    {
        return new View(
            $this->container->get($this->serviceName)->findAll($request->query->all()),
            Response::HTTP_OK
        );
    }

    /**
     * @Rest\Get("/job/{id}")
     *
     * @param id
     * @throws NotFoundHttpException
     * @return View
     */
    public function getAction($id): View
    {
        return parent::getAction($id);
    }

    /**
     * @Rest\Post("/job")
     */
    public function postAction(Request $request): View
    {
        return parent::postAction($request);
    }

    /**
     * @Rest\Put("/job/{id}")
     *
     * @param id
     * @param Request $request
     * @return View
     */
    public function putAction(String $id, Request $request): View
    {
        $params = $request->request->all();
        $params['id'] = $id;
        $entity = $this->builder::build($params);
        $persistedEntity = $this->container->get($this->serviceName)->update($entity);

        return new View(
            $persistedEntity,
            Response::HTTP_OK
        );
    }
}
