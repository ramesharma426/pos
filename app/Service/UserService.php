<?php

namespace App\Service;

use App\Exceptions\ErrorPageException;
use App\Repository\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @throws ErrorPageException
     */
    public function getAllUsers($paginator, $request )
    {
        return $this->userRepository->getAllUsers($paginator, $request);
    }

    /**
     * @throws ErrorPageException
     */
    public function userStore(\Illuminate\Support\ValidatedInput|array $request): void
    {
        $data = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role_id' => $request['role_id']
        ];

        if($this->userRepository->create($data))
            return;

        throw new ErrorPageException('Cannot Create New User', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function nameUpdateUser(\Illuminate\Support\ValidatedInput|array $request): void
    {
        if($this->userRepository->update($request['id'], ['name' => $request['name']]))
            return;

        throw new ErrorPageException('Cannot Create New User', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function emailUpdateEmail(\Illuminate\Support\ValidatedInput|array $request): void
    {
        if($this->userRepository->update($request['id'], ['email' => $request['email']]))
            return;

        throw new ErrorPageException('Cannot Create New User', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function passwordUpdateUser(array $request): void
    {
        if($this->userRepository->update($request['id'], ['password' => Hash::make($request['password'])]))
            return;

        throw new ErrorPageException('Cannot Create New User', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function roleUpdateUser(array $request): void
    {
        if($this->userRepository->update($request['id'], ['role_id' => $request['role_id']]))
            return;

        throw new ErrorPageException('Cannot Create New User', 500);
    }

    /**
     * @throws ErrorPageException
     */
    public function deleteUser($user_id): void
    {
        if($this->userRepository->destroy($user_id))
            return ;

        throw new ErrorPageException('Cannot Remove User', 500);
    }
}
