<?php
/**
 * Ulogin.ru auto registration or login.
 */
namespace App\Http\Controllers\Auth;

use App\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Redirect;
use App\Http\Controllers\Controller; 

class UloginController extends Controller
{

// Login user through social network.
    public function login(Request $request)
    {
        // Get information about user.
        $data = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
        $user = json_decode($data, TRUE);

        $network = $user['network'];

        // Find user in DB.
        $userData = User::where('email', $user['email'])->first();
        //var_dump($userData); exit;

        // Check exist user.
        if (isset($userData->id)) {
            Auth::loginUsingId($userData->id, TRUE);
            return redirect()->to('/');
        }
        // Make registration new user.
        else {

            // Create new user in DB.
            $newUser = User::create([
                //'nik' => $user['nickname'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                //'avatar' => $user['photo'],
                //'country' => $user['country'],
                'email' => $user['email'],
                'password' => Hash::make(str_random(8))
                //'role' => 'user',
                //'status' => TRUE,
                //'ip' => $request->ip()
            ]);

            // Make login user.
            Auth::loginUsingId($newUser->id, TRUE);

            \Session::flash('flash_message', trans('interface.ActivatedSuccess'));

            return redirect()->to('/');
        }
    }
}