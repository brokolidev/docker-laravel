<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validator($request);

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = auth()->user();
            $user->tokens()->delete();

            // 토큰을 발급한 뒤 첨부하여 리턴
            $token = $user->createToken('app-token');
            $user['token'] = $token;

            return $user;
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages([
            'email' => ["인증에 실패하였습니다 비밀번호를 확인해주세요"],
        ]);
    }

    public function logout()
    {
        $user = auth()->user();
        $user->tokens()->delete(); // 발급받은 토큰을 모두 삭제

        return ['result' => true, 'message' => '로그아웃 성공'];
    }

    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'email'    => 'required|exists:users|email|max:100',
            'password' => 'required|string|min:10',
        ];

        //custom validation error messages.
        $messages = [
            'email.required' => '가입된 이메일을 입력해주세요',
            'email.exists' => '가입되지 않음 이메일 주소입니다',
            'email.email' => '올바른 형식의 이메일 주소를 입력해주세요',
            'email.max' => '이메일 주소는 100자 이하로 입력해주세요',
            'password.required' => '비밀번호를 입력해주세요',
            'password.min' => '비밀번호는 10자 이상 입력해주세요',
            'password.string' => '비밀번호는 영문 대문자, 영문 소문자, 특수문자만 입력 가능합니다',
        ];

        //validate the request.
        return $request->validate($rules, $messages);
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }
}
