<?php

use Illuminate\Support\Facades\Redirect;

class UsersController extends \BaseController {

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

    /**
     * @return mixed
     */
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
        $response['email'] = Input::get('email');
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
                $response['alerts'][] = array(
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

                    return Redirect::action('DashboardController@getHome');
                }
                catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
                {
                    $response['alerts'][] = array(
                        'type' => 'danger',
                        'title' => 'Error',
                        'message' => trans('users.login_field'));
                }
                catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
                {
                    $response['alerts'][] = array(
                        'type' => 'danger',
                        'title' => 'Error',
                        'message' => trans('users.login_field'));
                }
                catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
                {
                    $response['alerts'][] = array(
                        'type' => 'danger',
                        'title' => 'Error',
                        'message' => trans('users.wrong_password'));
                }
                catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
                {
                    $response['alerts'][] = array(
                        'type' => 'danger',
                        'title' => 'Error',
                        'message' => trans('users.wrong_user'));
                }
                catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
                {
                    $response['alerts'][] = array(
                        'type' => 'danger',
                        'title' => 'Error',
                        'message' => trans('users.user_not_activated'));
                }
            }


        return View::make('admin.users.login-form', $response);
	}

    /**
     * @return mixed
     */
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
        $response['input'] = Input::get();

        $validator = Validator::make(
            Input::get(),
            array(
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password'
            )
        );

        if ($validator->fails())
        {
            $response['alerts'][] = array(
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
                    'first_name'    => Input::get('first_name'),
                    'last_name'    => Input::get('last_name'),
                    'email'    => Input::get('email'),
                    'password' => Input::get('password'),
                ), true);

                UserInfo::create(array('user_id' => $user->id));

                //Setting first user to admin
                if(count(Sentry::all()) === 1){
                    $user->addGroup(Sentry::findGroupByName('Administrator'));
                }else{
                    $user->addGroup(Sentry::findGroupByName('User'));
                }

                $response['alerts'][] = array(
                    'type' => 'success',
                    'title' => 'Success',
                    'message' => trans('users.registration_ok'));

            }
            catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
            {
                $response['alerts'][] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => trans('users.login_field'));
            }
            catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
            {
                $response['alerts'][] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => trans('users.password_field'));
            }
            catch (Cartalyst\Sentry\Users\UserExistsException $e)
            {
                $response['alerts'][] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => trans('users.user_exists'));
            }
        }
        return View::make('admin.users.registration-form', $response);
	}

    /**
     * @param $user_id
     * @return mixed
     */
    public function getProfile($user_id)
	{
        $response = array();
        $response['user'] = DB::table('users')
            ->where('users.id', '=', $user_id)
            ->join('users_info', 'users_info.user_id', '=', 'users.id')
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.activated_at', 'users.last_login',
                'users_info.age', 'users_info.skype', 'users_info.website', 'users_info.picture')->first();

        $response['albums'] = $albums = DB::table('albums')->where('user_id', '=', $user_id);
        $response['photos'] = $photos = DB::table('albums')
            ->where('user_id', '=', $user_id)
            ->join('photos', 'photos.album_id', '=', 'albums.id');

        $response['no_comments'] = $albums->sum('no_comments') + $photos->sum('photos.no_comments');
        $response['no_likes'] = $albums->sum('no_likes') + $photos->sum('photos.no_likes');
        $response['no_followers'] = UserFollow::where('following_id', '=', $user_id)->count();
        $response['no_following'] = UserFollow::where('follower_id', '=', $user_id)->count();
        $response['following'] = $this->canFollow($user_id);
        $response['can_edit'] = $this->canAccess('admin', false, $user_id);

        Breadcrumbs::addCrumb('Profile', URL::action('UsersController@getProfile', array('user_id' => $user_id)));

        return View::make('admin.users.profile', $response);
	}

    /**
     * @return mixed
     */

    public function postProfilePicture()
    {
        $file = Input::file('profile_picture');
        $user = Sentry::findUserById(Input::get('user_id'));
        $validator = Validator::make(
            array('profile_picture' => $file),
            array('profile_picture' => 'required|mimes:jpeg,bmp,png|max:2048')
        );

        if ($validator->fails())
        {
            $gritter[] = array(
                'type' => 'error',
                'title' => 'Error!',
                'message' => $validator->messages()->first());

            return Redirect::back()->with(array('gritter' => $gritter));
        }
        else
        {
            $new_file_name = $user->id . '.' . $file->getClientOriginalExtension();
            $tmp_file = $file->getRealPath();

            $gallery = new \Luknei\Gallery\Gallery();
            $gallery->setPhotoPath('public_users/pictures/');
            $gallery->setPhotoMaxWidth(200);
            $gallery->setPhotoMaxHeight(200);
            $gallery->setThumbPath('public_users/thumbs/');
            $gallery->setThumbWidth(64);
            $gallery->setThumbHeight(64);

            $create_thumb = $gallery->thumbnail($tmp_file, $new_file_name);
            $create_image = $gallery->image($tmp_file, $new_file_name);

            if($create_thumb && $create_image)
            {
                UserInfo::findById($user->id)->update(array('picture' => $new_file_name));

                $gritter[] = array(
                    'type' => 'success',
                    'title' => 'Success',
                    'message' => 'Profile picture was successfully changed.');

                return Redirect::back()->with(array('gritter' => $gritter));
            }
            else
            {
                $gritter[] = array(
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'The file was not uploaded, please try again.');

                return Redirect::back()->with(array('gritter' => $gritter));
            }
        }
    }

    /**
     * @return mixed
     */
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

            $response['alerts'][] = array(
                'type' => 'success',
                'title' => 'Success',
                'message' => trans('users.reset_password'));

        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $response['alerts'][] = array(
                'type' => 'danger',
                'title' => 'Error',
                'message' => trans('users.user_not_found'));
        }

        return View::make('admin.users.reset-password', $response);

	}

    /**
     * @return mixed
     */
    public function getChangePassword()
    {
        return View::make('admin.users.change-password');
    }

    /**
     * Changing user password with given reset code
     * @param $reset_code
     * @return mixed
     */
    public function postChangePassword($reset_code)
	{
        $new_password = Input::get('password');
        $new_password_repeat = Input::get('confirm_password');
        $msg = 'Please enter a new password';
        $response['alerts'][] = array(
            'type' => 'info',
            'title' => 'Info',
            'message' => 'Please enter a new password');

        if($new_password === $new_password_repeat)
        {
            try
            {
                // Find the user using the user id
                $user = $user = Sentry::findUserByResetPasswordCode($reset_code);

                // Attempt to reset the user password
                if ($user->attemptResetPassword($reset_code, $new_password))
                {
                    return Redirect::action('UsersController@getLogin');
                }
                else
                {
                    $response['alerts'][] = array(
                        'type' => 'error',
                        'title' => 'Error',
                        'message' =>  tans('users.password_change_error'));
                }

            }
            catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
            {
                $response['alerts'][] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => trans('users.reset_code_not_found'));
            }
        }
        else
        {
            $response['alerts'][] = array(
                'type' => 'danger',
                'title' => 'Error',
                'message' => trans('users.passwords_do_not_match'));
        }
        return View::make('admin.users.change-password', $response);
	}

    /**
     * @return mixed
     */
    public function getUsers()
    {
        $this->canAccess('admin');

        Breadcrumbs::addCrumb('Users');

        $response['users'] = DB::table('users')
            ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->leftJoin('groups', 'users_groups.group_id', '=', 'groups.id')
            ->select('users.id', 'users.email', 'users.first_name', 'users.last_name', 'users.created_at', 'users.activated_at', 'groups.name')
            ->get();

        return View::make('admin.users.get-users', $response);
    }

    /**
     * @return mixed
     */
    public function postUserEdit()
    {
        $this->canAccess('admin', true);

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

    /**
     * @return mixed
     */
    public function postUpdateProfile()
    {
        //WHO CAN ACCESS
        $user = Sentry::findUserById(Input::get('pk'));
        $this->canAccess('admin', true, $user->id);

        $input = Input::only('name', 'value');
        $validator = Validator::make(
            $input,
            array(
                'name' => 'required',
            )
        );
        if(!$validator->fails())
        {
            $main_table = array('first_name', 'last_name');
            $name = $input['name'];
            $value = $input['value'];

            if(in_array($name, $main_table))
            {
                $user->update(array($name => $value));
            }else{
                UserInfo::findById($user->id)->update(array($name => $value));
            }

            return Response::make('ok', 200);
        }else{
            return Response::make('failed', 404);
        }
    }

    /**
     *
     */
    public function postFollow()
    {
        if(Input::has('id'))
        {
            $following_id = Input::get('id');
            $follower_id = Sentry::getUser()->id;

            UserFollow::create(array(
                'following_id' => $following_id,
                'follower_id' => $follower_id
            ));

        }
    }

    /**
     *
     */
    public function postUnfollow()
    {
        if(Input::has('id'))
        {
            $following_id = Input::get('id');
            $follower_id = Sentry::getUser()->id;

            UserFollow::findByIds($following_id, $follower_id)->delete();
        }
    }

    /**
     * @return mixed
     */
    public function getUsersList()
    {
        $response['users'] = DB::table('users')
            ->join('users_info', 'users_info.user_id', '=', 'users.id')
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.activated_at', 'users.last_login',
                'users_info.age', 'users_info.skype', 'users_info.website', 'users_info.picture')->get();

        Breadcrumbs::addCrumb('Users list', URL::action('UsersController@getUsersList'));

        return View::make('admin.users.users-list', $response);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getFollowing($user_id)
    {
        $response['user'] = $user = Sentry::findUserById($user_id);
        $response['users'] = DB::table('users')
            ->where('users_follow.follower_id', '=', $user_id)
            ->join('users_follow', 'users_follow.following_id', '=', 'users.id')
            ->join('users_info', 'users_info.user_id', '=', 'users.id')
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.activated_at', 'users.last_login',
                'users_info.age', 'users_info.skype', 'users_info.website', 'users_info.picture')->paginate(30);

        Breadcrumbs::addCrumb($user->first_name . ' ' . $user->last_name, URL::action('UsersController@getProfile', array('user_id' => $user->id)));
        Breadcrumbs::addCrumb('Following');
        return View::make('admin.users.following', $response);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getFollowers($user_id)
    {
        $response['user'] = $user = Sentry::findUserById($user_id);
        $response['users'] = DB::table('users')
            ->where('users_follow.following_id', '=', $user_id)
            ->join('users_follow', 'users_follow.follower_id', '=', 'users.id')
            ->join('users_info', 'users_info.user_id', '=', 'users.id')
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.activated_at', 'users.last_login',
                'users_info.age', 'users_info.skype', 'users_info.website', 'users_info.picture')->paginate(30);

        Breadcrumbs::addCrumb($user->first_name . ' ' . $user->last_name, URL::action('UsersController@getProfile', array('user_id' => $user->id)));
        Breadcrumbs::addCrumb('Followers');
        return View::make('admin.users.followers', $response);
    }

    /**
     * @param $following_id
     * @return bool|null
     */
    protected function canFollow($following_id)
    {
        $follower_id = Sentry::getUser()->id;
        if($following_id == $follower_id){
            return null;
        }
        if(UserFollow::findByIds($following_id, $follower_id)->exists())
        {
            return false;
        }else{
            return true;
        }
    }
}