<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ODM\MongoDB\DocumentManager;

date_default_timezone_set('UTC');

use App\Document\Cart;
use App\Document\Catalogue;
use App\Document\User;
use App\Document\Order;
use App\Document\RateLimiter;


class CartController extends AbstractController
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

    private function productIDStringToJsonArray($productsId, DocumentManager $dm) : String
    {
        $productArray = explode(" ", $productsId);
        $data = array();
        foreach ($productArray as $id) {
            try {
                if (strlen($id) > 5) {
                    $info = $dm->getRepository(Catalogue::class)->find($id);
                    if ($info != null) {
                        $data[] = $this->serializeCatalogue($info);
                    }
                }
            } catch (\Exception $e) {
                unset($e);
            }
        }
        return (json_encode($data));
    }

    private function getTotPrice($productsId, DocumentManager $dm) : float
    {
        $productArray = explode(" ", $productsId);
        $data = 0.0;
        foreach ($productArray as $id) {
            try {
                if (strlen($id) > 5) {
                    $info = $dm->getRepository(Catalogue::class)->find($id);
                    if ($info != null) {
                        $data += $info->getPrice();
                    }
                }
            } catch (\Exception $e) {
                unset($e);
            }
        }
        return ($data);
    }

    private function checkIfUserCartIsCreated($userId, DocumentManager $dm) : void
    {
        $cart = $dm->getRepository(Cart::class)->findOneBy(['userId' => $userId]);

        if ($cart != null) {
            return;
        }
        $cart = new Cart();
        $cart->setUserId($userId);
        $dm->persist($cart);
        $dm->flush();
    }

    /**
     * @Route("/api/cart/{id}", name="addUniqueProductToCart", methods={"POST"} )
     */
    public function addUniqueProductToCart(Request $request, DocumentManager $dm, $id): JsonResponse
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        $Userid = $this->checkHeader($request, $dm);
        if ($id == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $this->checkIfUserCartIsCreated($Userid, $dm);
            $cart = $dm->getRepository(Cart::class)->findOneBy(['userId' => $Userid]);
            if ($cart->getProductsId() != null) {
                $cart->setProductsId($cart->getProductsId() . " " . $id);
            } else {
                $cart->setProductsId($id);
            }
            $dm->persist($cart);
            $dm->flush();
            return new JsonResponse([
                'success' => true,
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
     * @Route("/api/cart/{id}", name="RemoveUniqueProductToCart", methods={"DELETE"} )
     */
    public function RemoveUniqueProductToCart(Request $request, DocumentManager $dm, $id): JsonResponse
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        $Userid = $this->checkHeader($request, $dm);
        if ($id == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $this->checkIfUserCartIsCreated($Userid, $dm);
            $cart = $dm->getRepository(Cart::class)->findOneBy(['userId' => $Userid]);
            $cart->setProductsId(preg_replace('/'.$id.'/', "", $cart->getProductsId(), 1));
            $dm->persist($cart);
            $dm->flush();
            return new JsonResponse([
                'success' => true,
            ], 203);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * @Route("/api/cart/", name="GetCart", methods={"GET"} )
     */
    public function GetCart(Request $request, DocumentManager $dm): Response
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        $Userid = $this->checkHeader($request, $dm);
        if ($Userid == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $this->checkIfUserCartIsCreated($Userid, $dm);
            $cart = $dm->getRepository(Cart::class)->findOneBy(['userId' => $Userid]);
            $data = $this->productIDStringToJsonArray($cart->getProductsId(), $dm);
            $response =  new Response($data, 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch (\Exception $e) {
            $response =  new Response(json_encode("{\"error\" : \"" + $e->getMessage() +"\"}"), 500);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * @Route("/api/cart/validate/", name="ActivateCart", methods={"POST"} )
     */
    public function ActivateCart(Request $request, DocumentManager $dm): Response
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        $Userid = $this->checkHeader($request, $dm);
        if ($Userid == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $this->checkIfUserCartIsCreated($Userid, $dm);
            $cart = $dm->getRepository(Cart::class)->findOneBy(['userId' => $Userid]);
            $order = new Order();
            $order->setCreationDate(date(DATE_RFC2822));
            $order->setUserId($Userid);
            $order->setProducts($this->productIDStringToJsonArray($cart->getProductsId(), $dm));
            $order->setTotalPrice($this->getTotPrice($cart->getProductsId(), $dm));

            $dm->persist($order);
            $dm->remove($cart);
            $dm->flush();

            return new JsonResponse([
                'success' => true,
            ], 203);
        } catch (\Exception $e) {
            $response =  new Response(json_encode("{\"error\" : \"" + $e->getMessage() +"\"}"), 500);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * @Route("/api/orders/", name="getOrders", methods={"GET"} )
     */
    public function getOrders(Request $request, DocumentManager $dm): Response
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        $Userid = $this->checkHeader($request, $dm);
        if ($Userid == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $this->checkIfUserCartIsCreated($Userid, $dm);
            $cart = $dm->getRepository(Order::class)->findall(['userId' => $Userid]);
            $data =  $this->get('serializer')->serialize($cart, 'json');
            $response = new Response($data, 200);
            $response->headers->set('Content-Type', 'application/json');
    
            return $response;
        } catch (\Exception $e) {
            $response =  new Response(json_encode("{\"error\" : \"" + $e->getMessage() +"\"}"), 500);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * @Route("/api/orders/{id}", name="getOrder", methods={"GET"} )
     */
    public function getOrder(Request $request, DocumentManager $dm, $id): Response
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        $Userid = $this->checkHeader($request, $dm);
        if ($Userid == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $this->checkIfUserCartIsCreated($Userid, $dm);
            $cart = $dm->getRepository(Order::class)->find($id);
            $data =  $this->get('serializer')->serialize($cart, 'json');
            $response = new Response($data, 200);
            $response->headers->set('Content-Type', 'application/json');
    
            return $response;
        } catch (\Exception $e) {
            $response =  new Response(json_encode("{\"error\" : \"" + $e->getMessage() +"\"}"), 500);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * @Route("/api/checkToken/", name="checkToken", methods={"POST"} )
     */
    public function checkToken(Request $request, DocumentManager $dm): Response
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
                return new JsonResponse([
                    'message' => 'Wrong token',
                ],403);
            }

            $user = $dm->getRepository(User::class)->find($id);
            if ($user == null) {
                return new JsonResponse([
                    'message' => 'Wrong token',
                ],403);
            }
            return new JsonResponse([
                'message' => 'ok',
            ],200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
    }

}
