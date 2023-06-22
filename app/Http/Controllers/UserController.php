<?php

namespace App\Http\Controllers;

use App\DTO\Response\Result\ErrorResponse;
use App\DTO\Response\Result\SuccessResponse;
use App\DTO\Response\User\UserResponse;
use App\Repositories\User\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): JsonResponse
    {
        $allUsers = $this->userRepository->getAllUsers();

        if (array_key_exists("error", $allUsers)) {
            return response()->json((new ErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $allUsers["error"]))->toArray(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json((new SuccessResponse(Response::HTTP_OK, $allUsers))->toArray(), Response::HTTP_OK);
    }

    public function getUserById(int $id): JsonResponse
    {
        $userById = $this->userRepository->getUserById($id);

        if (array_key_exists("error", $userById)) {
            return response()->json((new ErrorResponse(Response::HTTP_NOT_FOUND, $userById["error"]))->toArray(), Response::HTTP_NOT_FOUND);
        }

        return response()->json((new SuccessResponse(Response::HTTP_OK,  $this->convertResponseUser($userById)))->toArray(), Response::HTTP_OK);
    }

    function convertResponseUser(array $user): array
    {
        return (new UserResponse($user))->toArray();
    }
}
