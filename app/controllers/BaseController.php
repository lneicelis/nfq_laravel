<?php

class BaseController extends Controller {

    protected $crumb = array();
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

    public function crumbAdd($url, $title)
    {
        array_push($this->crumb, array('url' => $url, 'title' => $title));
    }

    public function crumbGet()
    {
        return $this->crumb;
    }

}
