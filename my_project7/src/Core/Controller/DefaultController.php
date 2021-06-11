<?php

namespace App\Core\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
/**
     * Load the site definition and redirect to the default page.
     *
     * @Route("/", methods={"GET"})
     */
    public function indexAction(){
        return new Response(
            json_encode(
                [
                    'message' => "First Page"
                ]),
                Response::HTTP_OK,
                [
                    'Content-type' => 'application/json'
                ]
        );
    }

}