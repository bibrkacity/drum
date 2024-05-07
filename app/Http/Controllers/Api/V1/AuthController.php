<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Authorization",
     *     description="Authorization and return API-token",
     *     tags={"Auth"},
     *
     *     @OA\Parameter(
     *          name="email",
     *          description="E-mail for login",
     *          required=true,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="password",
     *          description="Password",
     *          required=true,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="API-token",
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'string|required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return new JsonResponse(data: $errors->toJson(), status: 400, json: true);
        }

        $email = $request->get('email');
        $password = $request->get('password');

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return new JsonResponse(data: ['message' => 'Credentials is invalid'], status: 401, json: false);
        }

        $token = $user->createToken('start');

        return new JsonResponse(data: ['token' => $token->plainTextToken], status: 200, json: false);

    }

    public function hash(Request $request): string
    {
        $password = $request->get('password');

        return Hash::make($password);
    }
}
