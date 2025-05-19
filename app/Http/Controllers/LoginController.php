<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{

    public function login_index()
    {
       
        return view('login');
    }


    public function login(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email|max:255',
                'password' => 'required|string|max:255|min:6',
            ]);
            $remember = $request->has('remember');
            $user = User::where('email', $request->email)->first();

            if ($user) {

                if ($user->is_activated == 1) {

                    if (Hash::check($request->password, $user->password)) {

                        if (Auth::attempt($request->only('email', 'password'),$remember)) {

                            $deploy = DB::table('deploy')->where('user_id', $user->id)->first();
                            $companyName = DB::table('users')
                            ->select('users.company_id as companyId', 'users.email', 'users.id as userId', 'organizations.name as companyName')
                            ->join('organizations', 'users.organization_id', '=', 'organizations.id')
                            ->where('users.email', '=', $request->email)
                            ->first();
                            $databaseName = str_replace(' ', '_', strtolower($companyName->companyName));
                           
                            // dd($databaseName);
                        
                        
                        
                            if ($databaseName) {

                                $dbName = $databaseName;

                                // Update the database configuration
                                config(['database.connections.pgsql.database' => $dbName]);

                                // Purge the connection to force a reset
                                DB::purge('pgsql');

                                // Reconnect to the database
                                DB::reconnect('pgsql');

                                // Verify the connection
                                DB::connection('pgsql')->getPdo();

                                \Log::info('Connected to the database: ', ['db' => $dbName]);

                                session(['db_name' => $dbName]);
                                $token = Str::random(60);
                                Cache::put('api_token', $token);
                                Cache::put('api_token_' . $token, $user, 10800);
                                return redirect('/home');
                            } else {
                                $token = Str::random(60);
                                Cache::put('api_token', $token);
                                Cache::put('api_token_' . $token, $user, 10800);
                                return redirect('/home');
                            }
                        }
                    } else {

                        return redirect('login')->withErrors(['password' => 'The password is incorrect.']);
                    }
                } else {

                    return redirect('login')->withErrors(['email' => 'Your account is not activated yet. Please check your email to activate your account']);
                }
            } else {

                return redirect('login')->withErrors(['email' => 'The email does not exist.']);
            }
        } catch (\PDOException $e) {
            // Handle PDO-specific exceptions (e.g., connection issues)
            \Log::error("PDOException: " . $e->getMessage());
            echo "Database connection failed: " . $e->getMessage();
        } catch (\Exception $e) {
            return redirect('login')->withErrors(['email' => $e->getMessage()]);
        }
    }

    // Logout Function
    public function logout()
    {
        Cache::forget('api_token');
        Cache::forget('api_token_');
        Auth::logout();
        Session::flush();
        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}
