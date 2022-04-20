<?php

namespace Multi\Admin\Controllers;

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{

    public function indexAction()
    {
        $data = $this->mongo->products->find();
        // foreach($data as $k=>$v){
        //     echo '<pre>';
        //     print_r($v);
        //     die;
        // }
        $this->view->place = $data;
        if ($this->request->getPost('submit')) {
            // echo 'ddkjvhdkfvhkd';
            // die;
            $data = array(
                "product_name" => $_POST['product_name'],
                "customer_name" => $_POST['name'],
                "quantity" => $_POST['number'],
                "status" => 'paid',
                "ordered_date" => date('Y-m-d')
            );
            // print_r($data);
            // die;
            $this->mongo->order->insertOne($data);
        }
    }
    public function listOrdersAction()
    {
        $dataa = $this->mongo->order->find();
        $this->view->list = $dataa;
        if ($this->request->getPost('status')) {
            $id = $this->request->getPost('id');
            // print_r($id);
            // die;
            $status = $this->request->getPost('status');
            $ord_status = array(
                "status" => $status
            );
            $this->mongo->order->updateOne(["_id" => new MongoDB\BSON\ObjectID($id)], ['$set' => $ord_status]);
            $this->response->redirect('/order/listOrders');
        }
        if ($this->request->getPost('status_filter')) {
            $filter_status = $this->request->getPost('status_filter');
            $fil_status = array(
                "status" => $filter_status
            );
            $filtered = $this->mongo->order->find($fil_status);
            $this->view->list = $filtered;
        }
        if ($this->request->getPost('date_filter')) {
            $date_filter = $this->request->getPost('date_filter');
            // print_r($date_filter);
            if ($date_filter == "today") {
                // echo "kdfvdkjvdk";
                // die;
                $date = $this->mongo->order->find(['ordered_date' => date('Y-m-d')]);
                $this->view->list  = $date;
            }
            if ($date_filter == "thisweek") {
                $start_date = date("Y-m-d", strtotime("-1 week"));
                $end_date = date("Y-m-d");
                $orders = array('ordered_date' => ['$gte' => $start_date, '$lte' => $end_date]);
                $orders = $this->mongo->order->find($orders);
                $this->view->list = $orders;
            }
            if ($date_filter == "thismonth") {
                $start_date = date("Y-m-d", strtotime("first day of this month"));
                $end_date = date("Y-m-d");
                $orders = array('ordered_date' => ['$gte' => $start_date, '$lte' => $end_date]);
                $orders = $this->mongo->order->find($orders);
                $this->view->list = $orders;
            }
            if ($date_filter == "custom") {
                $html = '<div>
                <input type="text" name="start_date" placeholder="Start Date"><br>
                <input type="text" name="end_date" placeholder="End Date">
            </div>';
                // echo $html;
                // die;
                $this->view->div = $html;
            }
            if ($this->request->getPost('custom')) {
                $start_date = $this->request->getPost('start_date');
                $end_date = $this->request->getPost('end_date');
                // die($start_date.''.$end_date);
                $orders = array('ordered_date' => ['$gte' => $start_date, '$lte' => $end_date]);
                $orders = $this->mongo->order->find($orders);
                $this->view->list = $orders;
            }
            // die;
        }
    }
    public function orderListAction()
    {

        // if ($this->request->getPost()) {
        //     $chng = $_POST['status'];
        //     print_r($chng);
        //     die;
        // }
    }
}
