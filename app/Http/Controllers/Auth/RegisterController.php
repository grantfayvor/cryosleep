<?php

namespace App\Http\Controllers\Auth;

use App\Services\RolesAndClaimsService;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

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
    protected $redirectTo = '/';

    private $claimsService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RolesAndClaimsService $claimsService)
    {
        $this->middleware('guest');
        $this->claimsService = $claimsService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:124',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if($request->has('rf')) {
            $referral = $request->input('rf');
            User::where('referral_code', $referral)->increment('referrals');
        }

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $split = explode(" ", $data['full_name']);
        $initials = "";
        for($i = 0; $i < count($split); $i++) {
            $initials = $initials . str_split($split[$i])[0];
        }
        $user = User::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'referral_code' => str_random(16) . $initials
        ]);
        $this->claimsService->assignRole($user, 'USER');
        return $user;
    }

    public function showRegistrationForm()
    {
        return view('register');
    }
}
