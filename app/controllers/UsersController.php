<?php

use Illuminate\Support\Facades\Redirect;

class UsersController extends \BaseController {

    /**
     * User logout
     * @return mixed
     */
    public function logout()
    {
        // Logs the user out
        Sentry::logout();

        return Redirect::to('user/login');
    }

    /**
     * User checking login
     * @return mixed
     */
    public function login()
	{
        $email = Input::get('email');
        $password = Input::get('password');

        // Get the Throttle Provider
        $throttleProvider = Sentry::getThrottleProvider();

        // Disable the Throttling Feature
        $throttleProvider->disable();

        if(!empty($email) && !empty($password))
        {
            try
            {
                // Set login credentials
                $credentials = array(
                    'email'    => $email,
                    'password' => $password,
                );

                // Try to authenticate the user
                $user = Sentry::authenticate($credentials, false);

                return Redirect::to('/');
            }
            catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
            {
                $alerts[] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => trans('users.login_field'));
            }
            catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
            {
                $alerts[] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => trans('users.login_field'));
            }
            catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
            {
                $alerts[] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => trans('users.wrong_password'));
            }
            catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
            {
                $alerts[] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => trans('users.wrong_user'));
            }
            catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
            {
                $alerts[] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => trans('users.user_not_activated'));
            }
        }

        return View::make('users.login-form', array('email' => $email, 'alerts' => @$alerts));
	}

    /**
     * Showing registration form
     * @return mixed
     */
    public function registration()
	{
        return View::make('users.registration-form');
	}

    /**
     * Registering the user
     * @return mixed
     */
    public function register()
	{
        $email = Input::get('email');
        $password =Input::get('password');

        $validator = Validator::make(
            array(
                'email' => $email,
                'password' => $password
            ),
            array(
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6'
            )
        );

        if ($validator->fails())
        {
            $msg = $validator->messages();
            return View::make('users.registration-form', array('message' => $msg));
        }
        else
        {
            try
            {
                // Let's register a user.
                $user = Sentry::register(array(
                    'email'    => $email,
                    'password' => $password,
                ), true);

                $msg = trans('users.registration_ok');

            }
            catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
            {
                $msg = trans('users.login_field');
            }
            catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
            {
                $msg = trans('users.password_field');
            }
            catch (Cartalyst\Sentry\Users\UserExistsException $e)
            {
                $msg = trans('users.user_exists');
            }
        }
        return View::make('users.registration-form', array('message' => $msg));
	}

    /**
     * Showing user profile
     * @return mixed
     */
    public function profile()
	{
        $msg = 'Welcome!';
        $logout_url = URL::to('user/logout');
        $user = Session::get('cartalyst_sentry.0');
        $results = DB::select('select * from users where id = ?', array($user));

        return View::make('users.profile', array('user' => $user, 'message' => $msg, 'logout_url' => $logout_url));
	}

    /**
     * Sending reset code to the user
     * @return mixed
     */
    public function reset_password()
	{
        $email = Input::get('email');

        try
        {
            // Find the user using the user email address
            $user = Sentry::findUserByLogin($email);

            // Get the password reset code
            $resetCode = $user->getResetPasswordCode();
            $url = Redirect::to('/user/change_password/' . $resetCode);
            $data = array('url' => $url);

            // Now you can send this code to your user via email for example.
            Mail::send('emails.reset-code', $data, function($message)
            {
                $message->from('us@example.com', 'Laravel');

                $message->to('foo@example.com');

            });

            $msg = trans('users.reset_password');

        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $msg = trans('users.user_not_found');
        }

        return View::make('users.reset-password', array('message' => $msg));

	}

    /**
     * Changing user password with given reset code
     *
     * @param $reset_code
     * @return mixed
     */
    public function change_password($reset_code)
	{
        $new_password = Input::get('password');
        $new_password_repeat = Input::get('password_repeat');
        $msg = 'Please enter a new password';

        if(!empty($new_password) && !empty($new_password_repeat))
        {
            if($new_password === $new_password_repeat)
            {
                try
                {
                    // Find the user using the user id
                    $user = $user = Sentry::findUserByResetPasswordCode($reset_code);

                    // Attempt to reset the user password
                    if ($user->attemptResetPassword($reset_code, $new_password))
                    {
                        $msg = tans('users.password_change_ok');
                        Redirect::to('user/login');
                    }
                    else
                    {
                        $msg = tans('users.password_change_error');
                    }

                }
                catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
                {
                    $msg = tans('users.reset_code_not_found');
                }
            }
            else
            {
                $msg = trans('users.password_do_not_match');
            }
        }
        return View::make('users.change-password', array('message' => $msg));
	}

}