<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\School;
use App\Models\User;
use App\Support\SchoolResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login for mobile app - automatically detects network and school from email
     * 
     * @bodyParam email string required User email. Example: user@example.com
     * @bodyParam password string required User password. Example: password
     * @bodyParam device_name string optional Device name for token. Example: iPhone 15 Pro
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        // Find user by email (globally unique)
        $user = User::withoutGlobalScopes()
            ->where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if user is active
        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been deactivated. Please contact your school administrator.'],
            ]);
        }

        // Check if user is archived
        if ($user->trashed()) {
            throw ValidationException::withMessages([
                'email' => ['This account has been archived. Please contact your school administrator.'],
            ]);
        }

        // Get user's available schools/contexts
        $availableContexts = $user->availableContexts();
        
        if ($availableContexts->isEmpty()) {
            throw ValidationException::withMessages([
                'email' => ['No school access found for this account. Please contact your administrator.'],
            ]);
        }

        // Determine default context (first available school)
        $defaultContext = $availableContexts->first();
        $school = $defaultContext->school;
        $network = null;

        // For super admins, they don't need a school context
        if (!$user->is_super_admin && $school) {
            // Load school relationship
            $school->load('network');
            $network = $school->network;
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Create token
        $deviceName = $request->device_name ?? $request->userAgent() ?? 'Mobile Device';
        $token = $user->createToken($deviceName)->plainTextToken;

        // Map available contexts
        $contextsArray = $availableContexts->map(function ($context) {
            return [
                'school_id' => $context->school_id,
                'school_slug' => $context->school->slug,
                'school_name' => $context->school->name,
                'network_slug' => $context->school->network->slug ?? null,
                'network_name' => $context->school->network->name ?? null,
                'role' => $context->role,
            ];
        });

        $response = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_super_admin' => $user->is_super_admin,
                'is_main_admin' => $user->is_main_admin,
            ],
            'available_contexts' => $contextsArray,
            'token' => $token,
        ];

        // Add current context only if user has a school context
        if ($network && $school) {
            $response['current_context'] = [
                'network' => [
                    'id' => $network->id,
                    'slug' => $network->slug,
                    'name' => $network->name,
                ],
                'school' => [
                    'id' => $school->id,
                    'slug' => $school->slug,
                    'name' => $school->name,
                ],
            ];
        }

        return response()->json($response);
    }

    /**
     * Get authenticated user information
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $availableContexts = $user->availableContexts()->map(function ($context) {
            return [
                'school_id' => $context->school_id,
                'school_slug' => $context->school->slug,
                'school_name' => $context->school->name,
                'network_slug' => $context->school->network->slug ?? null,
                'network_name' => $context->school->network->name ?? null,
                'role' => $context->role,
            ];
        });

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_super_admin' => $user->is_super_admin,
                'is_main_admin' => $user->is_main_admin,
            ],
            'available_contexts' => $availableContexts,
        ]);
    }

    /**
     * Switch school context (if user has multiple schools)
     */
    public function switchContext(Request $request)
    {
        $request->validate([
            'network' => 'required|string',
            'school' => 'required|string',
        ]);

        $user = $request->user();

        $network = Network::where('slug', $request->network)->first();
        if (!$network) {
            return response()->json([
                'message' => 'Network not found.',
            ], 404);
        }

        $school = School::where('slug', $request->school)
            ->where('network_id', $network->id)
            ->first();

        if (!$school) {
            return response()->json([
                'message' => 'School not found in this network.',
            ], 404);
        }

        // Verify user has access to this school
        $hasAccess = false;
        
        if ($user->is_super_admin) {
            $hasAccess = true;
        } elseif ($user->isMainAdmin() && $user->network_id === $network->id) {
            $hasAccess = true;
        } else {
            $hasAccess = $user->schools()->where('schools.id', $school->id)->exists();
        }

        if (!$hasAccess) {
            return response()->json([
                'message' => 'You do not have access to this school.',
            ], 403);
        }

        return response()->json([
            'current_context' => [
                'network' => [
                    'id' => $network->id,
                    'slug' => $network->slug,
                    'name' => $network->name,
                ],
                'school' => [
                    'id' => $school->id,
                    'slug' => $school->slug,
                    'name' => $school->name,
                ],
            ],
        ]);
    }

    /**
     * Logout - revoke current token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out.',
        ]);
    }

    /**
     * Logout from all devices - revoke all tokens
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out from all devices.',
        ]);
    }
}
