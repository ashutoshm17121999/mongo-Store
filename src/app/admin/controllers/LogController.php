<?php

namespace Multi\Admin\Controllers;

use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Mvc\Controller;


class LogController extends Controller
{
    public function indexAction()
    {
    }
    public function registerAction()
    {
        if ($this->request->getPost()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $collection = $this->mongo->users->findOne(['email' => $email, 'password' => $password]);
            if ($email == $collection->email && $password == $collection->password) {
                $adapter = new Stream('../app/admin/logs/log.log');
                $logger  = new Logger(
                    'messages',
                    [
                        'main' => $adapter,
                    ]
                );
                $logger->info("User Logged in successfully.");
                header('location:/login/index');
            } elseif ($email == "") {
                echo "Please enter correct email or password!!";
            } else {
                echo "not authorized!!";
            }
        }
    }
}
