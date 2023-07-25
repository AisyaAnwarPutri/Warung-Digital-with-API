<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller{
	public function index(){
		return view('auth.login');
	}

	public function do_login(Request $request){
		$this->validate($request, [
			'email' => 'required|email',
			'password' => 'required',
		]);
		$credentials = request(['email', 'password']);
		if(auth()->attempt($credentials)){
			return response()->json([
					'success' => true,
					'message' => 'Login Berhasil',
			]);
		}
		return response()->json([
			'success' => false,
			'message' => 'email atau password salah'
		]);
	}

	public function register(Request $request){
		$validator = Validator::make($request->all(), [
			'nama_member' => 'required',
			'provinsi' => 'required',
			'kabupaten' => 'required',
			'kecamatan' => 'required',
			'detail_alamat' => 'required',
			'no_hp' => 'required',
			'email' => 'required|email',
			'password' => 'required|same:konfirmasi_password',
			'konfirmasi_password' => 'required|same:password',
		]);
		if ($validator->fails()) {
			return response()->json(
					$validator->errors(),
					422
			);
		}
		$input = $request->all();
		$input['password'] = bcrypt($request->password);
		unset($input['konfirmasi_password']);
		$Member = Member::create($input);
		return response()->json([
			'data' => $Member
		]);
	}

	public function login_member(){
		return view('auth.login_member');
	}

	public function login_member_action(Request $request){
		$validator = Validator::make($request->all(), [
			'email' => 'required|email',
			'password' => 'required',
		]);
		if ($validator->fails()) {
			Session::flash('errors', $validator->errors()->toArray());
			return redirect('/login_member');
		}
		$credentials = $request->only('email', 'password');
		$member = Member::where('email', $request->email)->first();
		if ($member) {
			if (Auth::guard('webmember')->attempt($credentials)) {
					$request->session()->regenerate();
					return redirect('/');
			} else {
					Session::flash('failed', "Password salah");
					return redirect('/login_member');
			}
		} else {
			Session::flash('failed', "Email Tidak ditemukan");
			return redirect('/login_member');
		}
	}

	public function register_member(){
		return view('auth.register_member');
	}

	public function register_member_action(Request $request){
		// return $request->all();
		$validator = Validator::make($request->all(), [
			'nama_member' => 'required',
			'provinsi' => 'required',
			'kabupaten' => 'required',
			'kecamatan' => 'required',
			'detail_alamat' => 'required',
			'no_hp' => 'required',
			'email' => 'required|email',
			// 'password' => 'required|same:konfirmasi_password',
			'password' => 'required',
			'konfirmasi_password' => 'required|same:password',
		],[
			'nama_member.required' => 'Nama member harus diisi',
			'provinsi.required' => 'Provinsi harus diisi',
			'kabupaten.required' => 'Kabupaten harus diisi',
			'kecamatan.required' => 'Kecamatan harus diisi',
			'detail_alamat.required' => 'Alamat lengkap harus diisi',
			'no_hp.required' => 'No HP harus diisi',
			'email.required' => 'Email harus diisi',
			'email.email' => 'Email tidak valid',
			'password.required' => 'Password harus diisi',
			'konfirmasi_password.required' => 'Konfirmasi password harus diisi',
			'konfirmasi_password.same' => 'Password tidak sama',
		]);
		if ($validator->fails()) {
			$msg = '';
			foreach ($validator->errors()->toArray() as $key => $value) {
				$msg = $value[0]; break;
			}
			return ['success'=>false, 'message'=>$msg];
			// Session::flash('errors', $validator->errors()->toArray());
			// Session::flash('errors', $msg);
			// return redirect('/register_member');
		}
		$input = $request->all();
		$input['password'] = Hash::make($request->password);
		unset($input['konfirmasi_password']);
		Member::create($input);
		// Session::flash('success', 'Akun Berhasil Dibuat!');
		return ['success'=>true, 'message'=>'Akun berhasil dibuat'];
		// return redirect('/login_member');
	}

	public function logout(){
		Session::flush();
		return redirect('/login');
	}

	public function logout_member(){
		Auth::guard('webmember')->logout();
		Session::flush();
		return redirect('/login_member');
	}
}
