<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    protected function canAccess($group, $abort = false, $user_id = false)
    {

        if($user_id !== false)
        {
            if(Sentry::getUser()->id == $user_id)
            {
                return true;
            }
        }

        if(!Sentry::getUser()->hasAccess($group))
        {
            if($abort)
            {
                App::abort(404, 'You do not have access to this page!');
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
}
