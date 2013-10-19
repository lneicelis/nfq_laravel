<?php

class UsersController extends \BaseController {

	public function login()
	{
        return View::make('users.login-form');
	}

    public function logout()
    {
        // Logs the user out
        Sentry::logout();

        return Redirect::to('user/login');
    }

	public function auhenticate()
	{
        $email = Input::get('email');
        $password = Input::get('password');

        try
        {
            // Set login credentials
            $credentials = array(
                'email'    => $email,
                'password' => $password,
            );

            // Try to authenticate the user
            $user = Sentry::authenticate($credentials, false);

            return Redirect::to('user/profile');
        }
        catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            $msg = 'Login field is required.';
        }
        catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
        {
            $msg = 'Password field is required.';
        }
        catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
        {
            $msg = 'Wrong password, try again.';
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $msg = 'User was not found.';
        }
        catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
        {
            $msg = 'User is not activated.';
        }

        return View::make('users.login-form', array('email' => $email, 'message' => $msg));
	}


	public function registration()
	{
        return View::make('users.registration-form');
	}

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
            try
            {
                // Let's register a user.
                $user = Sentry::register(array(
                    'email'    => $email,
                    'password' => $password,
                ), true);

                $msg = 'User was successfully created';

            }
            catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
            {
                $msg = 'Login field is required.';
            }
            catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
            {
                $msg = 'Password field is required.';
            }
            catch (Cartalyst\Sentry\Users\UserExistsException $e)
            {
                $msg = 'User with this login already exists.';
            }

        return View::make('users.registration-form', array('message' => $msg));
	}

	public function profile()
	{
        if ( ! Sentry::check())
        {
            return Redirect::to('users/login');
        }
        else
        {
            $msg = 'Welcome!';
            $logout_url = URL::to('user/logout');
            $user = Session::get('cartalyst_sentry.0');
            $results = DB::select('select * from users where id = ?', array($user));
            var_dump($results);
            return View::make('users.profile', array('user' => $user, 'message' => $msg, 'logout_url' => $logout_url));
        }
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}