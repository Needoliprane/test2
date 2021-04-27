<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ODM\MongoDB\DocumentManager;

use App\Document\Catalogue;
use App\Document\User;
use App\Document\RateLimiter;


class CatalogueController extends AbstractController
{
    private function rateLimiter(Request $request, DocumentManager $dm) : Bool
    {
        $date = new \DateTime();
        $client = $dm->getRepository(RateLimiter::class)->findOneBy(['ip' => $request->getClientIp()]);
        if ($client == NULL) {
            $client = new RateLimiter();
            $client->setIp($request->getClientIp());
            $client->setLastRequestTimeStamp($date->getTimestamp());
            $client->setCount(1);
            $dm->persist($client);
            $dm->flush();
            return (true);
        }
        if ($date->getTimestamp() - $client->getLastRequestTimeStamp() > $_ENV["RATE_LIMITE_TIMES"]) {
            $client->setCount(0);
            $client->setLastRequestTimeStamp($date->getTimestamp());
            $dm->persist($client);
            $dm->flush();
            return (true);
        }
        if ($_ENV["RATE_LIMITE"] <= $client->getCount()) {
            $client->setCount($client->getCount() + 1);
            $dm->persist($client);
            $dm->flush();
            return (false);
        }
        if ($_ENV["RATE_LIMITE"] > $client->getCount()) {
            $client->setCount($client->getCount() + 1);
            $client->setLastRequestTimeStamp($date->getTimestamp());
            $dm->persist($client);
            $dm->flush();
            return (true);
        }
        return false;
    }

    private function checkHeader(Request $request, DocumentManager $dm) : String
    {
        try {
            $header_base64 = $request->headers->get('authorization');
            $date = new \DateTime();
            $actualTimeStamp =  $date->getTimestamp();
            $header = base64_decode($header_base64);
            $headerSplit = explode("-", $header);
            $tokenTimeStamp = intval($headerSplit[0]);
            $id = $headerSplit[1];

            if ($actualTimeStamp - $tokenTimeStamp > $_ENV["TOKEN_LIFE"]) {
                return "";
            }

            $user = $dm->getRepository(User::class)->find($id);
            if ($user == null) {
                return "";
            }
            return $id;
        } catch (\Exception $e) {
            return "";
        }
    }

    private function serializeCatalogue(Catalogue $catalogue)
    {
        return array(
            "name" => $catalogue->getName(),
            "description" => $catalogue->getDescription(),
            "photo" => $catalogue->getPhoto(),
            "price" => $catalogue->getPrice(),
            "id" => $catalogue->getId()
        );
    }

    /**
     * @Route("/api/product/", name="listProduct", methods={"GET"} )
     */
    public function listProduct(DocumentManager $dm): Response
    {
        try {
            $catalogue = $dm->getRepository(Catalogue::class)
                ->findAll();
            $dm->flush();
            $data = array();
            foreach ($catalogue as $cat) {
                $data[] = $this->serializeCatalogue($cat);
            }
            $response =  new Response(json_encode($data), 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch (\Exception $e) {
            $response =  new Response(json_encode("{\"error\" : \"" + $e->getMessage() +"\"}"), 500);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * @Route("/api/product/{id}", name="uniqueProduct", methods={"GET"} )
     */
    public function uniqueProduct(Request $request, DocumentManager $dm, $id): JsonResponse
    {
        try {
            $catalogue = $dm->getRepository(Catalogue::class)
                ->find($id);
            $dm->flush();
            if ($catalogue == NULL) {
                return new JsonResponse([
                    'success' => false,
                    'error'    => "Product not found",
                ],404);
            }
            return new JsonResponse([
                'success' => true,
                "name" => $catalogue->getName(),
                "description" => $catalogue->getDescription(),
                "photo" => $catalogue->getPhoto(),
                "price" => $catalogue->getPrice()
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * @Route("/admin/product/{id}", name="removeUniqueProduct", methods={"DELETE"} )
     */
    public function removeUniqueProduct(Request $request, DocumentManager $dm, $id): JsonResponse
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        if ($this->checkHeader($request, $dm) == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $catalogue = $dm->getRepository(Catalogue::class)
                ->find($id);
            if ($catalogue == NULL) {
                return new JsonResponse([
                    'success' => false,
                    'error'    => "Product not found",
                ],404);
            }
            $dm->remove($catalogue);
            $dm->flush();
            return new JsonResponse([
                'success' => true,
            ]
            , 203);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * @Route("/admin/product/", name="addUniqueProduct", methods={"POST"} )
     */
    public function addUniqueProduct(Request $request, DocumentManager $dm): JsonResponse
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        if ($this->checkHeader($request, $dm) == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $catalogue = new Catalogue();
            $catalogue->setName($request->request->get("name"));
            $catalogue->setDescription($request->request->get("description"));
            $catalogue->setPhoto($request->request->get("photo"));
            $catalogue->setPrice($request->request->get("price"));
            $dm->persist($catalogue);
            $dm->flush();
            return new JsonResponse([
                'success' => true,
                "id" => $catalogue->getId()
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
            ],500);
        }
    }

        /**
     * @Route("/api/product/{id}", name="updateUniqueProduct", methods={"PUT"} )
     */
    public function updateUniqueProduct(Request $request, DocumentManager $dm, $id): JsonResponse
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        if ($this->checkHeader($request, $dm) == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $catalogue = $dm->getRepository(Catalogue::class)
                ->find($id);

            if ($catalogue == NULL) {
                return new JsonResponse([
                    'success' => false,
                    'error'    => "Product not found",
                ],404);
            }
            $catalogue->setName($request->request->get("name"));
            $catalogue->setDescription($request->request->get("description"));
            $catalogue->setPhoto($request->request->get("photo"));
            $catalogue->setPrice($request->request->get("price"));
            $dm->persist($catalogue);
            $dm->flush();
            return new JsonResponse([
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
            ],500);
        }
    }
}
