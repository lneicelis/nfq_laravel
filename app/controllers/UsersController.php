<?php

use Illuminate\Support\Facades\Redirect;

class UsersController extends \BaseController {

    public function __construct(){
        Breadcrumbs::addCrumb('Home', URL::action('AlbumsController@index'));
    }

    /**
     * User logout
     * @return mixed
     */
    public function getLogout()
    {
        // Logs the user out
        Sentry::logout();

        return Redirect::action('UsersController@getLogin');
    }

    public function getLogin()
    {
        return View::make('admin.users.login-form');
    }

    /**
     * User checking login
     * @return mixed
     */
    public function postLogin()
	{
        // Set login credentials
        $credentials = array(
            'email'    => Input::get('email'),
            'password' => Input::get('password')
        );

        // Get the Throttle Provider
        $throttleProvider = Sentry::getThrottleProvider();

        // Disable the Throttling Feature
        $throttleProvider->disable();

        $validator = Validator::make(
            $credentials,
            array(
                'email' => 'required',
                'password' => 'required'
            )
        );

            if ($validator->fails())
            {
                $alerts[] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => $validator->messages()->first());
            }else{
                try
                {

                    // Try to authenticate the user
                    $user = Sentry::authenticate($credentials, false);

                    if(Input::has('remember'))
                    {
                        Sentry::loginAndRemember($user);
                    }

                    return Redirect::action('DashboardController@getIndex');
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


        return View::make('admin.users.login-form', array(
            'alerts' => @$alerts,
            'email' => $credentials['email']));
	}

    public function getRegister()
    {
        return View::make('admin.users.registration-form');
    }

    /**
     * Registering the user
     * @return mixed
     */
    public function postRegister()
	{
        $email = Input::get('email');
        $password =Input::get('password');
        $confirm_password = Input::get('confirm_password');

        $validator = Validator::make(
            array(
                'email' => $email,
                'password' => $password,
                'confirm_password' => $confirm_password
            ),
            array(
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password'
            )
        );

        if ($validator->fails())
        {
            $alerts[] = array(
                'type' => 'danger',
                'title' => 'Error',
                'message' => $validator->messages()->first());
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


                $user->addGroup(Sentry::findGroupByName('User'));

                $alerts[] = array(
                    'type' => 'success',
                    'title' => 'Success',
                    'message' => trans('users.registration_ok'));

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
                    'message' => trans('users.password_field'));
            }
            catch (Cartalyst\Sentry\Users\UserExistsException $e)
            {
                $alerts[] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => trans('users.user_exists'));
            }
        }
        return View::make('admin.users.registration-form', array(
            'alerts' => @$alerts,
            'email' => @email));
	}

    /**
     * Showing user profile
     * @return mixed
     */
    public function getProfile()
	{
        $msg = 'Welcome!';
        $logout_url = URL::to('user/logout');
        $user = Session::get('cartalyst_sentry.0');
        $results = DB::select('select * from users where id = ?', array($user));

        return View::make('admin.users.profile', array('user' => $user, 'message' => $msg, 'logout_url' => $logout_url));
	}

    public function getResetPassword()
    {
        return View::make('admin.users.reset-password');
    }

    /**
     * Sending reset code to the user
     * @return mixed
     */
    public function postResetPassword()
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

            $alerts[] = array(
                'type' => 'success',
                'title' => 'Success',
                'message' => trans('users.reset_password'));

        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $alerts[] = array(
                'type' => 'danger',
                'title' => 'Error',
                'message' => trans('users.user_not_found'));
        }

        return View::make('admin.users.reset-password', array('alerts' => @$alerts));

	}

    public function getChangePassword()
    {
        return View::make('admin.users.change-password');
    }

    /**
     * Changing user password with given reset code
     *
     * @param $reset_code
     * @return mixed
     */
    public function postChangePassword($reset_code)
	{
        $new_password = Input::get('password');
        $new_password_repeat = Input::get('confirm_password');
        $msg = 'Please enter a new password';

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
                    return Redirect::action('UsersController@getLogin');
                }
                else
                {
                    $alerts[] = array(
                        'type' => 'success',
                        'title' => 'Success',
                        'message' =>  tans('users.password_change_error'));
                }

            }
            catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
            {
                $alerts[] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => trans('users.reset_code_not_found'));
            }
        }
        else
        {
            $alerts[] = array(
                'type' => 'danger',
                'title' => 'Error',
                'message' => trans('users.passwords_do_not_match'));
        }
        return View::make('admin.users.change-password', array('alerts' => $alerts));
	}

    public function getUsers()
    {
        $this->canAccess('admin');

        Breadcrumbs::addCrumb('Users');

        $users = User::get(array('id', 'email', 'first_name', 'last_name', 'permissions', 'created_at', 'activated_at'))->toJSON();
        $users = DB::table('users')
            ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->leftJoin('groups', 'users_groups.group_id', '=', 'groups.id')
            ->select('users.id', 'users.email', 'users.first_name', 'users.last_name', 'users.created_at', 'users.activated_at', 'groups.name')
            ->get();

        return View::make('admin.users.get-users', array('users' => $users));
    }

    public function postCreateGroup()
    {
        try
        {
            // Create the group
            $group = Sentry::createGroup(array(
                'name'        => 'User',
                'permissions' => array(
                    'admin' => 0,
                    'moderator' => 0,
                    'user' => 1,
                ),
            ));
        }
        catch (Cartalyst\Sentry\Groups\NameRequiredException $e)
        {
            echo 'Name field is required';
        }
        catch (Cartalyst\Sentry\Groups\GroupExistsException $e)
        {
            echo 'Group already exists';
        }
    }

    public function postUserEdit()
    {
        $this->canAccess('admin');

        $validator = Validator::make(
            Input::get(),
            array(
                'id' => 'required|integer',
                'name' => 'required',
            )
        );

        if(!$validator->fails())
        {
            $user = Sentry::findUserById(Input::get('id'));
            $user->first_name = Input::get('first_name');
            $user->last_name = Input::get('last_name');
            $user->save();

            DB::table('users_groups')->where('user_id', '=', $user->id)->delete();

            $user->addGroup(Sentry::findGroupByName(Input::get('name')));

            return Response::make('', 200);
        }else{
            return Response::make($validator->messages()-first(), 404);
        }
    }

}