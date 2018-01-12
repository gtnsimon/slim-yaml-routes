<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class AccountsController
{
    public function login(Request $request, Response $response): ResponseInterface
    {
        return $response->write("");
    }
}