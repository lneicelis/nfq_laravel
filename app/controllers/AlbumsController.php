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
		$albums = Album::where('user_id', '=', $user_id)->get();

        return View::make('albums.albums-list', array('albums' => $albums));
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

        if($validator->fails())
        {
            $msg = $validator->messages();
        }
        else
        {
            $user_id = Sentry::getUser()->id;

            Album::create(array(
                'user_id' => $user_id,
                'title' => $title
            ));
            $msg = array('The album was successfully created.');
        }


        return View::make('albums.new-album', array('message' => $msg));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

        if(Album::find($id) === null)
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
            $msg = array('blabla');
        }

        return View::make('albums.photos-list', array('message' => $msg, 'photos' => $photos, 'album_id' => $id));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
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