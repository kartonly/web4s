<?php

declare(strict_types=1);

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Main
{
    /**
     * Load the site definition and redirect to the default page.
     *
     * @Route("/aaaa")
     */
    public function indexAction(Request $request)
    {
        return new Response(
            json_encode(
                [
                    'message' => 'HomePage',
                ],
                JSON_THROW_ON_ERROR
            ),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json'
            ]
        );
    }
}
