<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    public function register(Request $request)
    {

        // 유효성 검사
        $validated = $this->validator($request);
        $user = User::create([
            'name' => $validated['name'],
            'nickname' => $validated['nickname'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
        ]);

        return $user; // 가입에 성공하면 해댱 user instance를 반환
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(Request $request)
    {
        $rules = [
            'name'    => 'required|string|alpha|max:20',
            'nickname' => 'required|regex:/^[a-z]+$/|max:30',
            // 'password' => 'required|min:10|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
            'password' => [
                'required',
                'min:10',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
            ],
            'passwordCheck' => 'required|same:password',
            'phone' => 'required|digits_between:9,11',
            'email' => 'required|max:100|email|unique:users',
            'gender' => 'max:1|nullable'
        ];

        $messages = [
            'name.required' => '이름을 입력해주세요',
            'name.string' => '이름은 한글, 영문 대소문자만 입력 가능합니다',
            'name.alpha' => '이름은 한글, 영문 대소문자만 입력 가능합니다',
            'name.max' => '이름은 20자 이하로 입력해주세요',
            'nickname.required' => '별명을 입력해주세요',
            'nickname.reged' => '별명은 영문 소문자만 입력 가능합니다',
            'nickname.max' => '별명은 30자 이하로 입력해주세요',
            'password.required' => '비밀번호를 입력해주세요',
            'password.min' => '비밀번호는 최소 10자리 이상이어야 합니다',
            'password.regex' => '비밀번호는 영문 대문자, 영문 소문자, 특수문자, 숫자가 최소 1자 이상 포함되어야 합니다',
            'passwordCheck.required' => '비밀번호 확인란에 비밀번호를 한 번 더 입력해주세요',
            'passwordCheck.same' => '작성한 비밀번호와 비밀번호 확인이 서로 다릅니다',
            'phone.required' => '전화번호를 입력해주세요',
            'phone.digits_between' => '전화번호는 9자자리 ~ 11자리 숫자만 입력 가능합니다',
            'email.required' => '이메일을 입력해주세요',
            'email.max' => '이메일은 100자 이내로 입력해주세요',
            'email.email' => '올바른 이메일 형식이 아닙니다',
            'email.unique' => '이미 사용중인 이메일 주소입니다',
            'gender.max' => '성별 정보가 올바르지 않습니다'
        ];

        return $request->validate($rules, $messages);
    }
}
