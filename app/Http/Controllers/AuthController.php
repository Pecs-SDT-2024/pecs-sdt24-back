<?php

namespace App\Http\Controllers;

use App\Models\DataResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ErrorResponse;
use App\Models\JwtResponse;
use App\Models\SuccessResponse;
use App\Models\UserResponse;
use App\Models\UserRoleMapping;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login_post', 'register']]);
    }

    public function login_post(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json(new ErrorResponse('Unauthorized'), 401);
        }

        // Encode roles into the JWT token.
        $user = Auth::user();
        $roles = [];
        foreach ($user->roles as $roleMapping) {
            $roles[] = $roleMapping->role_id;
        }
//        $claimToken = Auth::claims(['roles' => $roles])->fromUser($user);
        $claimToken = auth('api')->claims(['roles' => $roles])->attempt($credentials);

        $responseData = new JwtResponse($claimToken, $this->getExpiration());

        return response()->json(new DataResponse($responseData));
    }

    public function login_get()
    {
        $user = Auth::user();
        $responseData = new UserResponse($user);

        return response()->json(new DataResponse($responseData));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        DB::transaction(function() use($request) {
            /**
             * @var User
             */
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            UserRoleMapping::create([
                'user_id' => $user->id,
                'role_id' => 1
            ]);
        });

        return response()->json(new SuccessResponse());
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(new SuccessResponse());
    }

    public function refresh()
    {
        $responseData = new JwtResponse(Auth::refresh(), $this->getExpiration());
        return response()->json(new DataResponse($responseData));
    }

    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * Returns the expiration time of the current token in seconds
     * @return int Expiration time
     */
    private function getExpiration() {
        return auth('api')->factory()->getTTL() * 60;
    }
}
