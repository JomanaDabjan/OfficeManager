<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials through structured security tiers.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        // =====================================================================
        // SECURITY LAYER 1: Pure IP-Based Rate Limiting (Brute-Force Shield)
        // =====================================================================
        $this->ensureIpIsNotRateLimited();

        // =====================================================================
        // SECURITY LAYER 2: Email & IP Combination Rate Limiting (Account Shield)
        // =====================================================================
        $this->ensureIsNotRateLimited();

        // =====================================================================
        // SECURITY LAYER 3: Database Record Validation (Existence Check)
        // =====================================================================
        $user = User::where('email', $this->input('email'))->first();

        if (! $user) {
            RateLimiter::hit($this->throttleKey());
            RateLimiter::hit($this->ipThrottleKey());

            throw ValidationException::withMessages([
                'email' => 'This email address is not registered in our system.',
            ]);
        }

        // =====================================================================
        // SECURITY LAYER 4: Password Verification & Authentication Guard
        // =====================================================================
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            RateLimiter::hit($this->ipThrottleKey());

            throw ValidationException::withMessages([
                'password' => 'The password you entered is incorrect. Please check your credentials.',
            ]);
        }

        // =====================================================================
        // SECURITY CLEANUP: Reset Failure Counters on Success
        // =====================================================================
        RateLimiter::clear($this->throttleKey());
        RateLimiter::clear($this->ipThrottleKey());
    }

    /**
     * Ensure the client's IP address is not exceeding overall request limits.
     *
     * @throws ValidationException
     */
    public function ensureIpIsNotRateLimited(): void
    {
        $ipKey = 'login-ip:' . $this->ip();

        if (RateLimiter::tooManyAttempts($ipKey, 20)) {
            $seconds = RateLimiter::availableIn($ipKey);

            throw ValidationException::withMessages([
                'email' => 'Too many login attempts from this IP address. Please try again in ' . ceil($seconds / 60) . ' minutes.',
            ]);
        }
    }

    /**
     * Ensure the specific Email + IP combination is not locked out due to repeated failures.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the combined email and IP tracking key for throttling.
     *
     * @return string
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }

    /**
     * Get a unique throttle key based strictly on the client's IP address alone.
     *
     * @return string
     */
    public function ipThrottleKey(): string
    {
        return 'login-ip:' . $this->ip();
    }
}