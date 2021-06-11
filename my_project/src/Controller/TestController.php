<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 */

class TestController
{
    /**
     * @Route(path="/", methods={"GET"})
     */

    public function index()
    {
        return new Response(
            json_encode(
                [
                    'value' => 123
                ]
            ),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/hello")
     */
    public function hello(): Response
    {
        return new Response(
            '<html><body>Hello!!!</body></html>'
        );
    }
}