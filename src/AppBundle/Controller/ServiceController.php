<?php

namespace AppBundle\Controller;

use AppBundle\Builder\Service as ServiceBuilder;
use AppBundle\Services\Service;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;

class ServiceController extends AbstractController
{
    public function __construct()
    {
        $this->serviceName = Service::class;
        $this->builder = ServiceBuilder::class;
    }

    /**
     * @Rest\Get("/service")
     * @return View
     */
    public function getAllAction(): View
    {
        return parent::getAllAction();
    }

    /**
     * @Rest\Get("/service/{id}")
     *
     * @param int id
     * @throws NotFoundHttpException
     * @return View
     */
    public function getAction($id): View
    {
        return parent::getAction($entity);
    }

    /**
     * @Rest\Post("/service")
     */
    public function postAction(Request $request): View
    {
        return parent::postAction($request);
    }
}
