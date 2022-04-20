<?php

namespace Multi\Admin\Controllers;

use Phalcon\Mvc\Controller;

class UserController extends Controller
{

    public function indexAction()
    {
    }

    public function signupAction()
    {

        if ($this->request->getPost()) {
            $data = $this->request->getPost();
            $this->mongo->users->insertOne($data);
            header('location:/log/');
        }
    }
}
