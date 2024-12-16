<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Models\PasswordReset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Mail\ActivateAccountMail;

class RegistrationController extends Controller
{
    // Registration page view with registration form
    public function register_index()
    {
        return view('register');
    }

    // Send Account actiovation email
    public function register(Request $request)
    {
      
        $request->validate([
            'username' => 'required|string|max:255|min:2|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|max:255|min:6|confirmed',
            'password_confirmation' => 'required|string|max:255|min:6',
        ]);
        
        try {
            $token =  sha1(Str::random(40)); 

            $domain = URL::to('/');
            $url = $domain . '/activate-account?token=' . $token; 

            $data['url'] = $url;
            $data['email'] = $request->email;
            $name = $request->username;
            $data['title'] = 'activate';
            $data['body'] = 'click on link to activate account';

        //    $mail = Mail::send('activateAccountMail', ['data' => $data], function ($message) use ($data) {
           
        //         $message->to($data['email'])->subject($data['title']);
        //     });
        // Mail::to($data['email'])->send(new ActivateAccountMail($data));


            User::create([
                'name' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'activation_token' => $token,
            ]);

            $email = $request->email;
            list($emailname, $domain) = explode('@', $email);
            $maskedEmailname = substr($emailname, 0, 2) . str_repeat('*', strlen($emailname) - 4) . substr($emailname, -2);
            $maskedEmail = $maskedEmailname . '@' . $domain;

            return redirect('login')->with('success', 'Activation mail has been sent to ' . $maskedEmail . '. Please check your email to activate your account.');
        } catch (\Illuminate\Database\QueryException $e) {         
            return redirect()->back()->withErrors(['error' => 'Failed to register. Please try again later.']);
        } catch (\Illuminate\Mail\MailerException $e) {         
            return redirect()->back()->withErrors(['error' => 'Failed to send activation email. Please contact support.']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

  
    public function activateAccount_index(Request $request)
    {
      
        $activationdata = User::where('activation_token', $request->token)->get();
 
        if (isset($request->token) && count($activationdata) > 0 && ($activationdata[0]['is_activated']) == 0) {

            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            $user = User::where('activation_token', $request->token)->first();

            if ($user) {
                $user->is_activated = 1;
                $user->email_verified_at = $datetime;
                $user->save();

                return redirect('/login')->with('success', 'Youe account has been activated successfully. You can login now');
                //return response()->json(['message' => 'Password reset successfully.'], 200);
            } else {
                abort(500);
            }
        } else {
            // if user is already activated then redirect to login page with success messaage
            return redirect('/login')->with('success', 'Youe account is already activated. You can login now');
        }
    }
}
