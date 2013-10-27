<?php

class AlbumsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $user_id = Sentry::getUser()->id;
        $default_cover = 'default.jpg';

        $albums = DB::table('albums')
            ->leftJoin('photos', 'albums.cover_photo', '=', 'photos.id')
            ->where('albums.user_id', '=', $user_id)
            ->select('albums.id', 'albums.title', 'albums.description', 'photos.file_name')
            ->get();

        if(empty($albums))
        {
            $alerts[] = array(
                'type' => 'info',
                'title' => 'Info',
                'message' => 'You do not have any albums yet.');
        }

        return View::make('albums.albums-list', array(
            'albums' => $albums,
            'default_cover' => $default_cover,
            'alerts' => @$alerts));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $title = Input::get('title');

        $validator = Validator::make(
            array(
                'title' => $title
            ),
            array(
                'title' => 'required',
            ),
            array(
                'required' => 'Enter new album title, please.'
            )
        );

        if(!empty($title))
        {
            if($validator->fails())
            {
                $msg = $validator->messages();

                $alerts[] = array(
                    'type' => 'danger',
                    'title' => 'Error',
                    'message' => 'There was a problem, the album was not created. Please try again.');
            }
            else
            {
                $user_id = Sentry::getUser()->id;

                Album::create(array(
                    'user_id' => $user_id,
                    'title' => $title
                ));

                $alerts[] = array(
                    'type' => 'success',
                    'title' => 'Success',
                    'message' => 'The album was successfully created.');
            }
        }else{
            $alerts[] = array(
                'type' => 'info',
                'title' => 'Info',
                'message' => 'Please enter the new album title.');
        }

        return View::make('albums.new-album', array('alerts' => $alerts));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function setCover($photo_id)
	{
        $user_id = Sentry::getUser()->id;

        $photo = DB::table('photos')
            ->select('photos.file_name', 'albums.id')
            ->leftJoin('albums', 'photos.album_id', '=', 'albums.id')
            ->whereRaw('albums.user_id = ? AND photos.id = ?', array($user_id, $photo_id))
            ->first();

        if(!empty($photo)){
            Album::find($photo->id)->update(array('cover_photo' => $photo_id));

            $gritter[] = array(
                'type' => 'success',
                'title' => 'Success',
                'message' => 'Cover photo have been changed.');
        }
        return Redirect::back()->with('gritter', $gritter);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $album = Album::find($id);

        if($album === null)
        {
            App::abort(404);
        }

        $photos = Photo::where('album_id', '=', $id)->get();

        if($photos->count() === 0)
        {
            $msg = array('Album is empty.');
        }
        else
        {
            $msg['gritter']['success'][] = array('blabla');
        }

        return View::make('albums.photos-list', array('message' => $msg, 'photos' => $photos, 'album' => $album));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit()
	{

		$album_id = Input::get('album_id');
		$title = Input::get('title');
		$description = Input::get('description');
        var_dump($album_id, $title, $description);
        $user_id = Sentry::getUser()->id;
        $album = Album::whereRaw('id = ? AND user_id = ?',array($album_id, $user_id))->first();

        if(!empty($album))
        {
            $validator = Validator::make(
                array(
                    'title' => $title,
                    'description' => $description,
                ),
                array(
                    'title' => 'required|max:100',
                    'description' => 'max:255'
                ),
                array(
                    'required' => 'Enter new album title, please.'
                )
            );
            if(!$validator->fails()){
                $album->title = $title;
                $album->description = $description;
                $album->save();

                return Redirect::back();
            }
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