<?php

use Phalcon\Mvc\Controller;

class UserController extends Controller
{

    public function IndexAction()
    {
    }
    
    public function signupAction()
    {

        if ($this->request->getPost()) {
            // print_r($this->request->getPost());
            // die();
            $user = new Users();
            $user->assign(
                $this->request->getPost(),
                [
                    'name',
                    'email'
                ]
            );
            $success = $user->save();
            $this->view->success = $success;
            if ($success) {
                $this->view->message = 'Successfully resgistered';
            } else {
                $this->view->message = 'sorry' . implode('<br>', $user->getMessages());
            }
        }
    }
}
