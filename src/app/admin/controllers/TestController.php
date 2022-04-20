<?php

namespace Multi\Admin\Controllers;

use Phalcon\Mvc\Controller;


class TestController extends Controller
{
    public function testconfigAction()
    {
        // $config = $this->get("config");
        $this->view->appname = $this->config->get('app')->get('name');
        //$this->view->appname = $this->config->app->name;
        $this->view->appnamee = $this->config->get('app')->get('version');

        // return '<h1>Hello World!</h1>';
    }
}
