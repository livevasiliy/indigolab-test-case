<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\RequestCodeDTO;
use App\Service\SmsCode\SmsCodeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestCodeController
{
    /**
     * @var SmsCodeService
     */
    private $smsCodeService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        SmsCodeService $smsCodeService,
        ValidatorInterface $validator
    )
    {
        $this->smsCodeService = $smsCodeService;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/request-code", methods={"post"}, name="request_code")
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = new RequestCodeDTO();
        $dto->phone_number = $data['phone_number'] ?? null;


        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        try {
            $code = $this->smsCodeService->requestCode($dto->phone_number);
            return new JsonResponse(['code' => $code]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_TOO_MANY_REQUESTS);
        }
    }
}
