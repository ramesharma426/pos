<?php

namespace App\Http\Controllers\Auth;

use App\Traits\Messages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    use Messages;

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated(), $request['remember']))
            return response()->json($this->getErrorMessage('Credentials did not match'), Response::HTTP_UNPROCESSABLE_ENTITY);
        $data = [
            'name' => Auth::user()->name,
            'role' => Auth::user()->role->role,
            'id' => Auth::id()
        ];
        return response()->json($data);
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json($this->getMessage('Logged Out'));
        } catch (\Exception $exp) {
            return response()->json($this->getErrorMessage('Cannot logout'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
