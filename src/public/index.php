<?php
// print_r(apache_get_modules());
// echo "<pre>"; print_r($_SERVER); die;
// $_SERVER["REQUEST_URI"] = str_replace("/phalt/","/",$_SERVER["REQUEST_URI"]);
// $_GET["_url"] = "/";
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Bag as SessionBag;
use Phalcon\Http\Response\Cookies;
use Phalcon\Mvc\Router;
//use Phalcon\Config;
use Phalcon\Config\ConfigFactory;
require_once("../app/vendor/autoload.php");
$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$loader->register();

$container = new FactoryDefault();


$container->set(
    'config',
    function () {
        $filename = '../app/etc/config.php';
        $config = new Config([]);
        $array =  new \Phalcon\Config\Adapter\Php($filename);
        $config->merge($array);
        return $config;
    },
    true
);

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);
$container->set(
    'router',
    function () {
        $router = new Router();

        $router->setDefaultModule('admin');

        $router->add(
            '/admin/log/:action',
            [
                'module'     => 'admin',
                'controller' => 'log',
                'action'     => 1,
            ]
        );

        $router->add(
            '/admin/login/:action',
            [
                'module'     => 'admin',
                'controller' => 'login',
                'action'     => 1,
            ]
        );
        $router->add(
            '/admin/user/:action',
            [
                'module'     => 'admin',
                'controller' => 'user',
                'action'     => 1,
            ]
        );
        // $router->add(
        //     '/admin/addproduct/:action',
        //     [
        //         'module'     => 'admin',
        //         'controller' => 'addproduct',
        //         'action'     => 1,
        //     ]
        // );
    
        $router->add(
            '/admin/order/:action',
            [
                'module'     => 'admin',
                'controller' => 'order',
                'action'     => 1,
            ]
        );
        // $router->add(
        //     '/signup/:action',
        //     [
        //         'module'     => 'front',
        //         'controller' => 'signup',
        //         'action'     => 1,
        //     ]
        // );

        $router->add(
            '/',
            [
                'module'     => 'admin',
                'controller' => 'log',
                'action'     => 'index',
            ]
        );

        // $router->add(
        //     '/products/:action',
        //     [
        //         'controller' => 'products',
        //         'action'     => 1,
        //     ]
        // );

        return $router;
    }
);

$application = new Application($container);
$application->registerModules(
    [
        'front' => [
            'className' => \Multi\Front\Module::class,
            'path'      => APP_PATH.'/front/Module.php',
        ],
        'admin'  => [
            'className' => \Multi\Admin\Module::class,
            'path'      => APP_PATH.'/admin/Module.php',
        ]
    ]
);


// $container->set(
//     'db',
//     function () {
//         return new Mysql(
//             [
//                 'host'     => $this['config']['db']->host,
//                 'username' => $this['config']['db']->username,
//                 'password' => $this['config']['db']->password,
//                 'dbname'   => $this['config']['db']->dbname
//             ]
//         );
//     }
// );
$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );

        $session
            ->setAdapter($files)
            ->start();

        return $session;
    }
);
$container->set(
    'cookies',
    function () {
        $cookies = new Cookies();

        $cookies->useEncryption(false);

        return $cookies;
    }
);
$container->set(
    'time',
    function () {
        return date('D-M-Y, h:m:s');
    }
);

// $container->set(
//     'mongo',
//     function () {
//         $mongo = new MongoClient();

//         return $mongo->selectDB('phalt');
//     },
//     true
// );
$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username"=>'root', "password"=>"password123"));
        // mongo "mongodb+srv://sandbox.g819z.mongodb.net/myFirstDatabase" --username root

        return $mongo->store;
    },
    true
);




try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
