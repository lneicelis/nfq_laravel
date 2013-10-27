<?php

class PhotosController extends \BaseController {

	public function edit($id)
	{

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

        $user_id = Sentry::getUser()->id;

        $photo = DB::table('photos')
            ->select('photos.file_name')
            ->leftJoin('albums', 'photos.album_id', '=', 'albums.id')
            ->whereRaw('albums.user_id = ? AND photos.id = ?', array($user_id, $id))
            ->first();

        if(!empty($photo)){

             @unlink('../public/images/' . $photo->file_name);
             @unlink('../public/thumbs/' . $photo->file_name);

            Photo::destroy($id);

            $gritter[] = array(
                'type' => 'success',
                'title' => 'Message',
                'message' => 'Photo has been successfully deleted.');
        }
        return Redirect::back()->with('gritter', $gritter);
	}

}