<?php

class PhotosController extends \BaseController {

    public function getUpload($album_id)
    {
        $album = Album::find($album_id);

        Breadcrumbs::addCrumb('Home', URL::action('AlbumsController@index'));
        Breadcrumbs::addCrumb('Gallery', URL::action('AlbumsController@index'));
        Breadcrumbs::addCrumb($album->title . ' album', URL::action('AlbumsController@show', array('id' => $album_id)));
        Breadcrumbs::addCrumb('Upload photos');

        return View::make('admin.albums.photos-upload-form', array(
            'album_id' => $album_id));
    }

    public function postUpload($album_id)
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

                $create_thumb = Gallery::thumbnail($tmp_file, $new_file_name, 200, 200);

                $create_image = Gallery::image($tmp_file, $new_file_name, 800, 800);

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

    public function edit()
	{
        $photo_id = Input::get('photo_id');
        $description = Input::get('description');

        $user_id = Sentry::getUser()->id;
        $photo = DB::table('photos')
            ->leftJoin('albums', 'photos.album_id', '=', 'albums.id')
            ->whereRaw('albums.user_id = ? AND photos.id = ?', array($user_id, $photo_id))
            ->first();

        if(!empty($photo))
        {
            $validator = Validator::make(
                array(
                    'description' => $description,
                ),
                array(
                    'description' => 'max:255'
                ),
                array(
                    'required' => 'Enter description, please.'
                )
            );
            if(!$validator->fails()){
                $photo = Photo::find($photo_id);
                $photo->description = $description;
                $photo->save();

                $gritter[] = array(
                    'type' => 'success',
                    'title' => 'Success',
                    'message' => 'Photo successfully edited.');
            }else{
                $gritter[] = array(
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => $validator->messages()->first());
            }

            return Redirect::back()->with(array('gritter' => $gritter));
        }
    }

	public function destroy()
	{

        $user_id = Sentry::getUser()->id;
        $id = Input::get('id');

        $photo = DB::table('photos')
            ->select('photos.file_name')
            ->leftJoin('albums', 'photos.album_id', '=', 'albums.id')
            ->whereRaw('albums.user_id = ? AND photos.id = ?', array($user_id, $id))
            ->first();

        if(!empty($photo)){

             unlink(public_path('gallery/images/' . $photo->file_name));
             unlink(public_path('gallery/thumbs/' . $photo->file_name));

            Photo::destroy($id);

            $gritter = array(
                'type' => 'success',
                'title' => 'Message',
                'message' => 'Photo has been successfully deleted.');

            return Response::json($gritter, 200);
        }

	}

    public function getPhotos(){

        $id = Input::get('id');

        $album = Album::find($id);

        if($album === null)
        {
            App::abort(404);
        }

        $photos = Photo::where('album_id', '=', $id)->get();

        return Response::json($photos, 200);

    }

    public function postTransfer()
    {
        $album_id = Input::get('album_id');
        $photo_id = Input::get('photo_id');

        $affectedRows = Photo::where('id', '=', $photo_id)->update(array('album_id' => $album_id));
        if($affectedRows > 0){
            return Response::json(200);
        }else{
            Return response::json(404);
        }

    }

    public function postCrop()
    {
        $x = (integer)Input::get('x');
        $y = (integer)Input::get('y');
        $w = (integer)Input::get('w');
        $h = (integer)Input::get('h');

        $validator = Validator::make(
            Input::get(),
            array(
                'photo-id' => 'required',
                'y' => 'required',
                'x' => 'required',
                'w' => 'required',
                'h' => 'required')
        );

        if(!$validator->fails())
        {
            $photo = Photo::find(Input::get('photo-id'));
            if(!empty($photo))
            {
                $new_file_name = str_random(16) . substr($photo->file_name, 16);

                if(Gallery::crop($photo->file_name, $new_file_name, $x, $y, $w, $h)){

                    Photo::create(array(
                        'album_id' => $photo->album_id,
                        'description' => $photo->description,
                        'file_name' => $new_file_name));

                    $gritter[] = array(
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => 'Photo successfully cropped. The new image has been created.');
                }else{
                    $gritter[] = array(
                        'type' => 'error',
                        'title' => 'Error',
                        'message' => 'The photo was not cropped, please try again.');
                }
            }
        }else{
            $gritter[] = array(
                'type' => 'error',
                'title' => 'Error',
                'message' => $validator->messages()->first());
        }

        return Redirect::back()->with(array('gritter' => $gritter));
    }

    public function postRotate($direction)
    {
        $rotate = ($direction === "left") ? 270 : 90;

        $photo = Photo::find(Input::get('id'));

        if(!empty($photo->id))
        {
            if(Gallery::rotate($photo->file_name, $photo->file_name, $rotate))
            {
                $gritter = array(
                    'type' => 'success',
                    'title' => 'Success',
                    'message' => 'Photo successfully rotated.');

                return Response::json($gritter, 200);
            }
        }
        return Response::json(404);
    }

    public function postStatus()
    {
        $photo = Photo::find(Input::get('id'));

        if(!empty($photo->id))
        {
            $status = ($photo->status === 1) ? 0 : 1;

            $photo->status = $status;

            $photo->save();

            $gritter = array(
                'type' => 'success',
                'title' => 'Success',
                'message' => 'Photo status successfully changed.');

            return Response::json($gritter, 200);
        }
        return Response::json(404);
    }
}