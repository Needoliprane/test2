<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ODM\MongoDB\DocumentManager;

use App\Document\Catalogue;

class OrderController extends AbstractController
{
    private function checkHeader(Request $request, DocumentManager $dm) : Bool
    {
        $request->headers->get('My-Header');
        return TRUE;
    }

}
