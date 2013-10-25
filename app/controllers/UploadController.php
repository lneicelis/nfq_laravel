<?php

class UploadController extends \BaseController {

    /**
     * Processing the uploaded file
     *
     * @return mixed
     */
    public function upload($album_id)
	{
        $user_id = Sentry::getUser()->id;
        $album = Album::whereRaw('id = ? AND user_id = ?', array($album_id, $user_id))->count();

        if($album === 0)
        {
            App::abort(404);
        }

        $msg = 'Select a photo, please.';
        if (Input::hasFile('userfile'))
        {
            $file = Input::file('userfile');
            $validator = Validator::make(
                array('userfile' => $file),
                array('userfile' => 'mimes:jpeg,bmp,png|max:2048')
            );

            if ($validator->fails())
            {
                $msg = $validator->messages();
            }
            else
            {
                $new_file_name = str_random(16) . '.' . $file->getClientOriginalExtension();

                $tmp_file = $_FILES['userfile']['tmp_name'];

                $create_thumb = $this->thumbnail($tmp_file, $new_file_name);

                $create_image = $this->image($tmp_file, $new_file_name);

                if($create_thumb && $create_image)
                {
                    Photo::create(array(
                        'user_id' => $user_id,
                        'album_id' => $album_id,
                        'file_name' => $new_file_name));

                    $msg = 'File successfully moved.';
                }
                else
                {
                    $msg = 'Failed to move the file.';
                }
            }
        }
        return View::make('upload.upload-form', array('message' => $msg, 'album_id' => $album_id));
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
        $save = '../public/thumbs/' . $save_name;

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
        $save = '../public/images/' . $save_name;

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