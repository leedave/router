<?php

namespace Leedch\Router;

use Leedch\Convert\Convert as C;
use Leedch\View\View;

/**
 * Handles all incoming requests
 *
 * @author leed
 */
class Router
{
    protected $activeRoutes = [];
    public $requestMethod;
    public $requestBody;
    public $requestPath;
    public $requestParams;

    public function setActiveRoutes(array $arrActiveRoutes)
    {
        $this->activeRoutes = $arrActiveRoutes;
    }

    public function route(): string
    {
        $this->getRequestData();

        foreach ($this->activeRoutes as $file) {
            $className = $file;
            $router = new $className();
            $routerResponse = $router->route($this);
            if ($routerResponse !== false) {
                return $routerResponse;
            }
        }

        return $this->return404();
    }

    /**
     * Returns the 404 Page
     * @return string HTML Code
     */
    protected function return404(): string
    {
        $view = new View();
        http_response_code(404);
        $response = View::loadView(leedch_templateError404);
        return $response;
    }


    /**
     * Collect all the request parameters
     */
    protected function getRequestData()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $arrFullUri = explode("?", $_SERVER['REQUEST_URI']);
        $this->requestPath = $arrFullUri[0];

        if (isset($arrFullUri[1])) {
            $this->requestParams = C::uriParams2Array($arrFullUri[1]);
        }

        $this->requestBody = $this->getRequestBody();

        /*echo "Method: ".print_r($this->requestMethod, true)."<br \>\n"
            . "Path: ".print_r($this->requestPath, true)."<br \>\n"
            . "Params: ".print_r($this->requestParams, true)."<br \>\n"
            . "Body: ".print_r($this->requestBody, true)."<br \>\n";*/
    }

    /**
     * Read the request body from php://input
     * @return string
     */
    protected function getRequestBody(): string
    {
        $body = file_get_contents('php://input');
        return $body;
    }
}
