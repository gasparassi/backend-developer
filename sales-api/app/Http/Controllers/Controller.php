<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FormRequest;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController implements FormRequest
{

    protected $service;
    protected $params;
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->params = $request->all();
    }

    public function getParams()
    {
        return $this->request->replace($this->params);
    }

}
