<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class PasswordResetController extends Controller
{

    // View after user clicks Forget password? option from login page containing email input form
    public function forgetPassword_index()
    {
        return view('forgetPassword');
    }

    // Generate a token and send a link via email to reset password
    public function forgetPassword(Request $request)
    {
        try {

            // Get all userdata of that email which is input from forgetPassword_index form, from USer table
            $user = User::where('email', $request->email)->get();

            // if user exists
            if (count($user) > 0) {
                $token =  sha1(Str::random(40)); // Generate a auth token
                $domain = URL::to('/');
                $url = $domain . '/reset-password?token=' . $token; // make a url containing auth token
                
                // Add all details in $data to send via email
                $data['url'] = $url;
                $data['email'] = $request->email;
                $user = User::where('email', $request->email)->first(['name']);
                $name = $user->name;
                $data['title'] = 'Reset your password';
                $data['body'] = 'click on link';

                // attempt to send email with url containing auth token
                Mail::send('resetPasswordMail', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });

                // get current time
                $datetime = Carbon::now()->format('Y-m-d H:i:s');

                //If one request for a email is already exists in PasswordReset table then only update auth token otherwise crate a new object
                PasswordReset::updateORCreate(
                    ['email' => $request->email],
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => $datetime,
                        'name' => $name,
                    ]
                );

                // Mast the mail like  co********97@gmail.com
                $email = $request->email;
                list($emailname, $domain) = explode('@', $email);
                $maskedEmailname = substr($emailname, 0, 2) . str_repeat('*', strlen($emailname) - 4) . substr($emailname, -2);
                $maskedEmail = $maskedEmailname . '@' . $domain;

                //Session::flush();
                return redirect('/login')->with('success', 'Mail to reset password has been sent to '.$maskedEmail.'. Please check your email to reset your password.');
                //return response()->json(['success' => true, 'msg' => 'Please check your mail']);
            } else {
                //return response()->json(['success' => false, 'msg' => 'No such credentials found']);
                return redirect('forget-password')->withErrors(['email' => 'No such credentials found.']);
            }
        } catch (\Exception $e) {
           // return response()->json(['success' => false, 'msg' => $e->getMessage()]);
           abort(500);
        }
    }

    // Validation after clicking on link which was sent via email
    public function resetPassword_index(Request $request)
    {
        // Get details of that corresponding auth token
        $resetdata = PasswordReset::where('token', $request->token)->get();

        // Check if auth token exixsts then allow to enter new password otherwise abort
        if (isset($request->token) && count($resetdata) > 0) {
            //echo $resetdata;
            $userdata = User::where('name', $resetdata[0]['name'])->get();
            //echo $userdata;
            return view('resetPassword', compact('userdata'));
        } else {
            abort(404);
        }
    }

    // Reset password form 
    public function resetPassword(Request $request)
    {
        // form validation
        $request->validate([
            'new_password' => 'required|string|max:255|min:6|confirmed',
            'new_password_confirmation' => 'required|string|max:255|min:6',
        ]);
    
        // Update new passsword
        $user = User::where('name', $request->username)->first();
    
        if ($user) {
            $user->password = Hash::make($request->new_password);
            $user->save();
    
            PasswordReset::where('name', $request->username)->delete();
    
            //Session::flush();
            return redirect('/login')->with('success', 'Your password has been reset. You can now login with your new password');
            //return response()->json(['message' => 'Password reset successfully.'], 200);
        } else {
            abort(500);
        }
    }
    
}
