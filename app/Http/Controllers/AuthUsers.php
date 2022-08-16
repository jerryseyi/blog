<?php


namespace App\Http\Controllers;


use App\Http\Requests\LoginRequest;
use App\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\HasApiTokens;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait AuthUsers
 * @package App\Http\Controllers
 */
trait AuthUsers
{
    use ThrottlesLogins, HasApiTokens;

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $loginRequest) {

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($loginRequest)) {

            $this->fireLockoutEvent($loginRequest);
            return $this->sendLockoutResponse($loginRequest);
        }

        if ($this->attemptLogin($loginRequest)) {
            return $this->sendLoginResponse($loginRequest);
        }

        $this->incrementLoginAttempts($loginRequest);
        return $this->sendFailedLoginResponse($loginRequest);
    }

    protected function attemptLogin(LoginRequest $loginRequest): bool
    {
        return $this->guard()->attempt(
            $this->credentials($loginRequest), $loginRequest->filled('remember')
        );
    }

    protected function guard()
    {
        return Auth::guard();
    }

    protected function credentials(LoginRequest $loginRequest): array
    {
        return $loginRequest->only($this->username(), 'password');
    }

    protected function sendLoginResponse(LoginRequest $loginRequest): array
    {
//        $loginRequest->session()->regenerate();

        $this->clearLoginAttempts($loginRequest);

        if ($response = $this->authenticated($loginRequest, $this->guard()->user())) {
            return $response;
        }
        return [
            'token' => $this->token($loginRequest),
            'user' => $this->guard()->user()
            ];

    }

    protected function token($loginRequest)
    {
        $user = User::where('email', $loginRequest->email)->first();
        return $user->createToken('Test application')->accessToken;
    }

    /**
     * @param LoginRequest $loginRequest
     * @param mixed $user
     * @return mixed
     */
    protected function authenticated(LoginRequest $loginRequest, $user)
    {
        //
    }

    /**
     * @param LoginRequest $loginRequest
     * @return Response
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(LoginRequest $loginRequest): Response
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    public function username(): string
    {
        return 'email';
    }
}
