<?php

class PhotosController extends \BaseController {

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
}