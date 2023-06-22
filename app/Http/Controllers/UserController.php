<?php

namespace App\Http\Controllers;

use App\DTO\Request\User\CreateUserRequest;
use App\DTO\Request\User\UpdateUserRequest;
use App\DTO\Response\Result\ErrorResponse;
use App\DTO\Response\Result\SuccessResponse;
use App\DTO\Response\User\UserResponse;
use App\Repositories\User\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function createUser(Request $request): JsonResponse
    {
        $validatedRequest =  (new CreateUserRequest($request->all()))->validate();

        if (array_key_exists("error", $validatedRequest)) {
            return response()->json((new ErrorResponse(Response::HTTP_BAD_REQUEST, $validatedRequest["error"]))->toArray(), Response::HTTP_BAD_REQUEST);
        }

        $createdUser = $this->userRepository->createUser($request->all());

        if (array_key_exists("error", $createdUser)) {
            return response()->json((new ErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $createdUser["error"]))->toArray(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json((new SuccessResponse(Response::HTTP_OK, $createdUser))->toArray(), Response::HTTP_OK);
    }

    public function updateUserById(Request $request, int $id): JsonResponse
    {

        $userById = $this->userRepository->getUserById($id);

        if (array_key_exists("error", $userById)) {
            return response()->json((new ErrorResponse(Response::HTTP_NOT_FOUND, $userById["error"]))->toArray(), Response::HTTP_NOT_FOUND);
        }

        $validatedRequest = (new UpdateUserRequest($request->all()))->validate();

        if (array_key_exists("error", $validatedRequest)) {
            return response()->json((new ErrorResponse(Response::HTTP_BAD_REQUEST, $validatedRequest["error"]))->toArray(), Response::HTTP_BAD_REQUEST);
        }

        $updatedUser = $this->userRepository->updateUserById($request->all(), $userById);

        if (array_key_exists("error", $updatedUser)) {
            return response()->json((new ErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $updatedUser["error"]))->toArray(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json((new SuccessResponse(Response::HTTP_OK, $updatedUser))->toArray(), Response::HTTP_OK);
    }

    public function deleteUserById(int $id): JsonResponse
    {
        $userById = $this->userRepository->getUserById($id);

        if (array_key_exists("error", $userById)) {
            return response()->json((new ErrorResponse(Response::HTTP_NOT_FOUND, $userById["error"]))->toArray(), Response::HTTP_NOT_FOUND);
        }

        $deletedUser = $this->userRepository->deleteUserById($userById["id"]);

        if (array_key_exists("error", $deletedUser)) {
            return response()->json((new ErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $deletedUser["error"]))->toArray(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json((new SuccessResponse(Response::HTTP_OK, $deletedUser))->toArray(), Response::HTTP_OK);
    }

    function convertResponseUser(array $user): array
    {
        return (new UserResponse($user))->toArray();
    }
}
