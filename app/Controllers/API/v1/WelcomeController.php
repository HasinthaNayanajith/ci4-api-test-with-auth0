<?php

namespace App\Controllers\API\v1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class WelcomeController extends ResourceController
{

    use ResponseTrait;

    protected $request;

    public function __construct()
    {
        $this->request = \Config\Services::request();
    }

    // test api endpoint - /api/v1/hello
    public function index()
    {
        if($this->request->is('get')){
            $response = [
                'status' => 200, // Success
                'error' => null,
                'message' => 'You have successfully sent a GET request.'
            ];
            return $this->respond($response);
            
        } else if($this->request->is('post')) {
            $response = [
                'status' => 200, // Not found
                'error' => null,
                'message' => 'You have successfully sent a POST request.'
            ];
            return $this->respond($response);
        } 
    }

}