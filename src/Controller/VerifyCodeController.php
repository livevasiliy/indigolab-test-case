<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SmsCodeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VerifyCodeController
{

    /**
     * @Route("/api/verify-code", methods={"POST"}, name="verify_code")
     */
    public function index(Request $request, UserRepository $userRepository, SmsCodeRepository $smsCodeRepository): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        $phoneNumber = $data['phone_number'] ?? null;
        $code = $data['code'] ?? null;

        if (!$phoneNumber || !$code) {
            return new JsonResponse(['error' => 'Phone number and code are required'], Response::HTTP_BAD_REQUEST);
        }

        $smsCode = $smsCodeRepository->findLatestByPhoneNumber($phoneNumber);

        if (!$smsCode || $smsCode->getCode() !== $code) {
            return new JsonResponse(['error' => 'Invalid code'], Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findByPhoneNumber($phoneNumber);

        if ($user) {
            return new JsonResponse(['message' => 'You have successfully logged in', 'user_id' => $user->getId()]);
        } else {
            $user = $userRepository->createUser($phoneNumber);
            return new JsonResponse(['message' => 'You have successfully registered', 'user_id' => $user->getId()]);
        }
    }
}
