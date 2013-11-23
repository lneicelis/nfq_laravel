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

    protected function canAccess($group)
    {
        if(!Sentry::getUser()->hasAccess($group))
        {
            App::abort(404, 'You do not have access to this page!');
        }
    }
    protected function canSee($group)
    {
        if(!Sentry::getUser()->hasAccess($group))
        {
            return false;
        }else{
            return true;
        }
    }

}
