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

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     * Apply the 'auth:api' middleware to all methods except 'login_post' and 'register'.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login_post', 'register']]);
    }

    /**
     * Handle POST request for user login.
     * Validate user credentials and generate JWT token with user roles as claims.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login_post(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Retrieve credentials from the request
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the user
        $token = Auth::attempt($credentials);
        if (!$token) {
            // Return unauthorized error if authentication fails
            return response()->json(new ErrorResponse('Unauthorized'), 401);
        }

        // Retrieve the authenticated user
        $user = Auth::user();

        // Collect user roles to include in the JWT claims
        $roles = [];
        foreach ($user->roles as $roleMapping) {
            $roles[] = $roleMapping->role_id;
        }

        // Generate a new token with roles as claims
        $claimToken = auth('api')->claims(['roles' => $roles])->attempt($credentials);

        // Prepare response data with the new token and its expiration time
        $responseData = new JwtResponse($claimToken, $this->getExpiration());

        // Return the response with the token and expiration time
        return response()->json(new DataResponse($responseData));
    }

    /**
     * Handle GET request for user login (alternative to login_post).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        return $this->login_get();
    }

    /**
     * Handle GET request for user login.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login_get()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Prepare response data with user details
        $responseData = new UserResponse($user);

        // Return the response with user details
        return response()->json(new DataResponse($responseData));
    }

    /**
     * Handle POST request for user registration.
     * Validate the request and create a new user and user role mapping within a transaction.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Perform database operations within a transaction
        DB::transaction(function () use ($request) {
            /**
             * @var User
             */
            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign a default role to the user
            UserRoleMapping::create([
                'user_id' => $user->id,
                'role_id' => 1
            ]);
        });

        // Return success response
        return response()->json(new SuccessResponse());
    }

    /**
     * Handle user logout.
     * Invalidate the JWT token and log the user out.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // Invalidate the token to log the user out
        Auth::invalidate(Auth::getToken());

        // Log the user out
        Auth::logout();

        // Return success response
        return response()->json(new SuccessResponse());
    }

    /**
     * Refresh the JWT token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // Generate a new token and prepare response data
        $responseData = new JwtResponse(Auth::refresh(), $this->getExpiration());

        // Return the response with the new token and expiration time
        return response()->json(new DataResponse($responseData));
    }

    /**
     * Get the authenticated user details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // Return the authenticated user details
        return response()->json(Auth::user());
    }

    /**
     * Get the expiration time of the current token in seconds.
     *
     * @return int Expiration time
     */
    private function getExpiration()
    {
        return auth('api')->factory()->getTTL() * 60;
    }
}
