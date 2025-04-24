<?php

namespace App\Services;

use App\Repositories\OfficerRepository;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class OfficerService
{

    private $repository;

    /**
     * @var Request
     */
    private $request;

    /**
     * ConferenceService constructor.
     * @param ConferenceRepository $repository
     * @param Request              $request
     */
    public function __construct(OfficerRepository $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request = $request;
    }

    /**
     * Get conference model
     *
     * @return Conference
     */
    public function getModel()
    {
        $officer_id = $this->request->route()->parameter('officer_id');
        return $this->repository->getByAlias($officer_id);
    }//end of getModel

}
