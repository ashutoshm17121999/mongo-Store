<?php

namespace Multi\Admin\Controllers;

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
            $add_variants['price'] = $price1;
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
            $this->mongo->products->insertOne($data);
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
    public function listProductsAction()
    {
        $dataa = $this->mongo->products->find();
        // foreach($dataa as $k=>$v){
        //     print_r($v);
        //     die;
        // }
        if ($this->request->getPost("search")) {
            // die('hi');
            $srch = $this->request->getPost('searchList');
            $result = array();

            foreach ($dataa as $k => $v) {
                if (strtolower($v->name) == strtolower($srch)) {
                    array_push($result, $v);
                    // die;
                    // $this->view->list = $v;
                } else {
                    $this->view->error = "<h3 class='alert alert-danger'>No Product Found !!</h3>";
                }
            }
            $this->view->result = $result;
        }

        // } else {
        //     $this->view->allResult = $dataa;
        // }
        // $collection = $this->mongo->find();
        // $this->view->products = $collection;
    }
    public function popupAction()
    {
    }
    public function updateListAction()
    {
        if ($this->request->getPost('delete')) {
            // echo 'fvkodjvfknkvnf';
            // die;
            $id = $this->request->getPost('id');
            $data = $this->mongo->products->deleteOne([
                "_id" => new MongoDB\BSON\ObjectID($id)
            ]);

            $this->response->redirect('/login/listProducts');
        }
        if ($this->request->getPost('edit')) {

            $id = $this->request->getPost('id');
            $data = $this->mongo->products->find([
                '_id' => new MongoDB\BSON\ObjectID($id)
            ]);
            foreach ($data as $k => $v) {
                $this->view->data = $v;
            }
            // $this->response->redirect('/login/updateList');
        }
    }

    public function updateProductAction()
    {
        $id = $this->request->getPost('id');
        // print_r($id);
        // die('sdkdfjvdfj');
        // $product_name = $this->request->getPost('product_name');
        // $product_category = $this->request->getPost('product_category');
        // $product_price = $this->request->getPost('product_price');
        // $product_stock = $this->request->getPost('product_stock');

        $field = $this->request->getPost('field');
        $value = $this->request->getPost('value');
        $additional = array_combine($field, $value);
        $data = array(
            "name" => $this->request->getPost('product_name'),
            "category" => $this->request->getPost('product_category'),
            "price" => $this->request->getPost('product_price'),
            "stock" => $this->request->getPost('product_stock'),
            "added_fields" => $additional
        );

        $this->mongo->products->updateOne(["_id" => new MongoDB\BSON\ObjectID($id)], ['$set' => $data]);
        $this->response->redirect('/login/listProducts');
    }
    
}
