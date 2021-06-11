<?php

namespace App\Api\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Api\Utils\HeaderUtils;

/**
 * @Route("/test")
 */

class TestController
{
    // /**
    //  * @Route(path="/", methods={"GET"})
    //  */

    // public function index(Request $request, HeaderUtils $headerutils)
    // {
    //     $authMetaData = $request-> headers -> get('Authorization');

    //     if($authMetaData != ''){

    //         [$type, $credentials] = explode(' ', $authMetaData);
    //         [$login, $pw] = explode(':', base64_decode($credentials));

    //         if ($headerutils -> check($login, $pw)){
    //             return new Response('Okay', Response::HTTP_OK);
    //         }

    //     } else {
    //         return new Response(
    //             'Not Authorized', 
    //             Response::HTTP_UNAUTHORIZED,
    //             [
    //                 'WWW-Authenticate' => 'Basic realm="Access to the staging site", charset="UTF-8"'
    //             ]
    //         );
    //     };

        // return new Response(
        //     json_encode(
        //         [
        //             'value' => 123
        //         ]
        //     ),
        //     Response::HTTP_OK,
        //     [
        //         'Content-type' => 'application/json'
        //     ]
        // );
    // }

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