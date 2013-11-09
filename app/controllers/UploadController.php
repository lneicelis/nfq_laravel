<?php

class UploadController extends \BaseController {

    public function upload($album_id)
    {
        $album = Album::find($album_id);

        Breadcrumbs::addCrumb('Home', URL::action('AlbumsController@index'));
        Breadcrumbs::addCrumb('Gallery', URL::action('AlbumsController@index'));
        Breadcrumbs::addCrumb($album->title . ' album', URL::action('AlbumsController@show', array('id' => $album_id)));
        Breadcrumbs::addCrumb('Upload photos');

        return View::make('upload.upload-form', array(
            'album_id' => $album_id));
    }

    /**
     * Processing the uploaded file
     *
     * @return mixed
     */
    public function process($album_id)
	{
        $user_id = Sentry::getUser()->id;
        $album = Album::whereRaw('id = ? AND user_id = ?', array($album_id, $user_id))->count();

        if($album === 0)
        {
            App::abort(404);
        }

        if (Input::hasFile('file'))
        {
            $file = Input::file('file');
            $validator = Validator::make(
                array('file' => $file),
                array('file' => 'mimes:jpeg,bmp,png|max:2048')
            );

            if ($validator->fails())
            {
                return Response::make($validator->messages()->first(), 400);
            }
            else
            {
                $new_file_name = str_random(16) . '.' . $file->getClientOriginalExtension();

                $tmp_file = $file->getRealPath();

                $create_thumb = $this->thumbnail($tmp_file, $new_file_name);

                $create_image = $this->image($tmp_file, $new_file_name);

                if($create_thumb && $create_image)
                {
                    Photo::create(array(
                        'album_id' => $album_id,
                        'description' => $file->getClientOriginalName(),
                        'file_name' => $new_file_name));

                    $alerts[] = array(
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => 'File was successfully uploaded.');

                    return Response::json('success', 200);
                }
                else
                {
                    $alerts[] = array(
                        'type' => 'danger',
                        'title' => 'Error!',
                        'message' => 'The file was not uploaded, please try again.');

                    return Response::json('error', 400);
                }
            }
        }
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */

	private function thumbnail($open, $save_name)
	{
        $width = 200;
        $height = 200;
        $save = public_path('gallery/thumbs/' . $save_name);

        try{
            $imagine = new \Imagine\Gd\Imagine();

            $crop_size = new \Imagine\Image\Box($width, $height);

            $mode_crop = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

            $imagine->open($open)
                ->thumbnail($crop_size, $mode_crop)
                ->save($save);

            return TRUE;
        }
        catch(Imagine\Exception\Exception $e)
        {
            return FALSE;
        }


	}

    private function image($open, $save_name)
    {
        $width = 800;
        $height = 800;
        $save = public_path('gallery/images/' . $save_name);

        try{
            $imagine = new \Imagine\Gd\Imagine();

            $resize_size = new \Imagine\Image\Box($width, 20000);

            if($imagine->open($open)->getSize()->getWidth() > $imagine->open($open)->getSize()->getHeight())
            {
                $resize_size = new \Imagine\Image\Box(20000, $height);
            }

            $mode_resize = Imagine\Image\ImageInterface::THUMBNAIL_INSET;

            $imagine->open($open)
                ->thumbnail($resize_size, $mode_resize)
                ->save($save);

            return TRUE;
        }
        catch(Imagine\Exception\Exception $e)
        {
            return FALSE;
        }
    }
}