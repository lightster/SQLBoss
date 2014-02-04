<?php

namespace SQLBoss\Controller;

use Symfony\Component\HttpFoundation\Response;

class Sessions
{
    public function __construct()
    {
        
    }

    public function indexAction()
    {
        return new Response('Hello World!');
    }
}
