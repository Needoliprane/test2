<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ODM\MongoDB\DocumentManager;

use App\Document\User;
use App\Document\RateLimiter;

class UserController extends AbstractController
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

    private function buildToken(String $id) : String
    {
        $date = new \DateTime();
        $res = "";
        $res .= strval($date->getTimestamp());
        $res .= "-";
        $res .= strval($id);
        return (base64_encode($res));
    }

    /**
     * @Route("/api/register", name="register", methods={"POST"} )
     */
    public function register(Request $request, DocumentManager $dm): JsonResponse
    {
        try {
            $user = new User();
            $user->setLogin($request->request->get("login"));
            $user->setPassword($request->request->get("password"));
            $user->setEmail($request->request->get("email"));
            $user->setFirstname($request->request->get("firstname"));
            $user->setLastname($request->request->get("lastname"));

            $dm->persist($user);
            $dm->flush();

            return new JsonResponse([
                'success' => true,
                'code'    => 201,
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
     * @Route("/api/login", name="login", methods={"POST"} )
     */
    public function login(Request $request, DocumentManager $dm): JsonResponse
    {
        try {
            $user = $dm->createQueryBuilder(User::class)
                ->field('login')->equals($request->request->get("login"))
                ->field('password')->equals($request->request->get("password"))
                ->getQuery()
                ->getSingleResult();
            if ($user != NULL) {
                return new JsonResponse([
                    'token' => $this->buildToken($user->getId()),
                ], 200);
            }
            return new JsonResponse([
                'error' => 'login failed',
            ], 401);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * @Route("/api/user", name="userUpdate", methods={"PUT"} )
     */
    public function userUpdate(Request $request, DocumentManager $dm): JsonResponse
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        $id = $this->checkHeader($request, $dm);
        if ($id == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $user = $dm->getRepository(User::class)->find($id);

            $user->setLogin($request->request->get("login"));
            $user->setPassword($request->request->get("password"));
            $user->setEmail($request->request->get("email"));
            $user->setFirstname($request->request->get("firstname"));
            $user->setLastname($request->request->get("lastname"));

            $dm->persist($user);
            $dm->flush();
            return new JsonResponse([
                'success' => true
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
     * @Route("/api/user", name="userDelete", methods={"DELETE"} )
     */
    public function userDelete(Request $request, DocumentManager $dm): JsonResponse
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        $id = $this->checkHeader($request, $dm);
        if ($id == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $user = $dm->getRepository(User::class)->find($id);
            $dm->remove($user);
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
     * @Route("/api/user", name="displayUser", methods={"GET"} )
     */
    public function displayUser(Request $request, DocumentManager $dm): JsonResponse
    {
        if ($this->rateLimiter($request, $dm) == false) {
            return new JsonResponse([
                'message' => 'too many request',
            ],429); 
        }
        $id = $this->checkHeader($request, $dm);
        if ($id == null) {
            return new JsonResponse([
                'message' => 'Wrong token',
            ],403);
        }
        try {
            $user = $dm->getRepository(User::class)->find($id);
            $dm->flush();
            return new JsonResponse([
                'success' => true,
                'login' => $user->getLogin($request->request->get("login")),
                'email' => $user->getEmail($request->request->get("email")),
                'firstname' => $user->getFirstname($request->request->get("firstname")),
                'lastname' => $user->getLastname($request->request->get("lastname")),
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
