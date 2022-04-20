<?php

namespace Multi\Admin\Controllers;

use Phalcon\Mvc\Controller;


class DashboardController extends Controller
{
    public function indexAction()
    {
        if (!$this->session->get('login')) {
            $this->response->redirect('login');
        }
        $this->view->time = $this->time;

        // return '<h1>Hello World!</h1>';
    }
    public function logoutAction()
    {

        //echo "getting value from session";
        $this->session->get('login');

        $this->session->remove('login');
        unset($session->email);
        $this->response->redirect('login');
    }
}
