<?php

use Phalcon\Http\Request;
use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Http\Response\Cookies;

class LoginController extends Controller
{
    public function indexAction()
    {
        // echo '<pre>';
        // print_r($this->request->getPost());
        // die;
        if ($this->request->getPost("addproduct")) {
            $key = $this->request->getPost('label');
            $value = $this->request->getPost('value');
            
            $add_fields = array_combine($key, $value);

            $key1 = $this->request->getPost('label1');
            $price1 = $this->request->getPost('price1');
            $value1 = $this->request->getPost('value1');
            $add_variants = array_combine($key1, $value1);
            $add_variants['price']=$price1;
            // $add_variants = $this->request->getPost('');
            // print_r($add_fields);
            // die;
            $data = array(
                'name' => $_POST['name'],
                'category' => $_POST['category'],
                'price' => $_POST['price'],
                'stock' => $_POST['stock'],
                'added_fields' => $add_fields,
                'added_variants' => $add_variants
            );
            // $data = $this->request->getPost();
            // $this->mongo->insertOne($data);
            $this->mongo->insertOne($data);
            // print_r($name);
            // die;
        }
        
        // if ($this->cookies->has('checkbox')) {
        //     $this->response->redirect('dashboard');
        // } else {
        //     if ($this->request->isPost()) {

        //         $email = $this->request->getPost('email');
        //         $password = $this->request->getPost('password');
        //         // print_r($email);
        //         // die();

        //         if (empty($email) || empty($password)) {
        //             $response = new Response();

        //             $response->setStatusCode(404, 'Fill the details');
        //             //$response->setContent("Sorry, the page doesn't exist");
        //             $response->send();
        //             //echo 'fill all the details';
        //             $this->session->set('login', '*****Fill all the details*****');
        //             //die();
        //         } else {
        //             $user = Users::findFirst(array(
        //                 'conditions' => 'email = :email: and password = :password:', 'bind' => array(
        //                     'email' => $this->request->getPost("email"),
        //                     'password' => $this->request->getPost("password")
        //                 )
        //             ));
        //             print_r($user);
        //             //die();
        //             if (!isset($user)) {
        //                 $response = new Response();

        //                 $response->setStatusCode(404, 'Wrong credentials');
        //                 //$response->setContent("Sorry, the page doesn't exist");
        //                 $response->send();
        //                 $this->session->set('login', 'Wrong user');
        //                 //die();
        //             } else {
        //                 if (isset($_POST['checkbox'])) {
        //                     $this->cookies->set(
        //                         'checkbox',
        //                         json_encode(
        //                             [
        //                                 'email' => $_POST['email'],
        //                                 'password' => $_POST['password']
        //                             ],
        //                             time() + 3600
        //                         )
        //                     );
        //                 }
        //                 $response = new Response();
        //                 $cookies  = new Cookies();

        //                 $response->setCookies($cookies);
        //                 $this->session->set('login', $email);
        //                 //$this->session->set('login', $password);
        //                 $this->response->redirect('dashboard');
        //                 //header("location:/dashboard");
        //             }
        //         }
        //     }
        // }
    }
}
