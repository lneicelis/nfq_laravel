<?php

class AlbumsController extends \BaseController {

    public function __construct(){
        Breadcrumbs::addCrumb('Home', URL::action('AlbumsController@index'));
        Breadcrumbs::addCrumb('Gallery', URL::action('AlbumsController@index'));
    }

	public function index($user_id = null)
	{

        if($user_id === null)
            $user_id = Sentry::getUser()->id;

        $default_cover = 'default.jpg';

        $albums = DB::table('albums')
            ->leftJoin('photos', 'albums.cover_photo', '=', 'photos.id')
            ->where('albums.user_id', '=', $user_id)
            ->select('albums.id', 'albums.user_id', 'albums.title', 'albums.description', 'albums.no_photos', 'albums.no_comments', 'albums.no_likes', 'photos.file_name')
            ->get();

        if(empty($albums))
        {
            $alerts[] = array(
                'type' => 'info',
                'title' => 'Info',
                'message' => 'There is no albums in the gallery.');
        }

        return View::make('admin.gallery.albums-list', array(
            'alerts' => @$alerts,
            'albums' => $albums,
            'default_cover' => $default_cover,
            'can_edit' => $this->canAccess('admin', false, $user_id)
        ));
	}


	public function postCreate()
	{
        $validator = Validator::make(
            Input::get(),
            array(
                'title' => 'required',
            ),
            array(
                'required' => 'Enter new album title, please.'
            )
        );

        if(!$validator->fails())
        {
            $user_id = Sentry::getUser()->id;

            Album::create(array(
                'user_id' => $user_id,
                'title' => Input::get('title'),
                'description' => Input::get('description')
            ));

            $gritter[] = array(
                'type' => 'success',
                'title' => 'Success',
                'message' => 'The album was successfully created.');

        }else{
            $gritter[] = array(
                'type' => 'error',
                'title' => 'Error',
                'message' =>  $validator->messages()->first());
        }

        return Redirect::back()->with(array('gritter' => $gritter));
	}

	public function postSetCover()
	{
        $user_id = Sentry::getUser()->id;
        $photo_id = Input::get('id');

        $photo = DB::table('photos')
            ->select('photos.file_name', 'albums.id')
            ->leftJoin('albums', 'photos.album_id', '=', 'albums.id')
            ->whereRaw('albums.user_id = ? AND photos.id = ?', array($user_id, $photo_id))
            ->first();

        if(!empty($photo)){
            Album::find($photo->id)->update(array('cover_photo' => $photo_id));

            $gritter = array(
                'type' => 'success',
                'title' => 'Success',
                'message' => 'Cover photo have been changed.');
            return Response::json($gritter, 200);
        }
        return Response::json(404);
	}

	public function show($id)
	{
        $album = Album::find($id);
        $albums = Album::where('id', '!=', $id)->get();

        if($album === null)
        {
            App::abort(404);
        }

        if($album->photos->count() === 0)
        {
            $alerts[] = array(
                'type' => 'info',
                'title' => 'Info',
                'message' => 'The album is empty.');
        }

        Breadcrumbs::addCrumb($album->title . ' album');

        return View::make('admin.gallery.photos-list', array(
            'alerts' => @$alerts,
            'album' => $album,
            'albums' => $albums,
            'photos' => $album->photos,
            'can_edit' => $this->canAccess('admin', false, $album->user_id)
        ));
	}

	public function postEdit()
	{

		$album_id = Input::get('album_id');
		$title = Input::get('title');
		$description = Input::get('description');
        $user_id = Sentry::getUser()->id;
        $album = Album::whereRaw('id = ? AND user_id = ?', array($album_id, $user_id))->first();

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

                $gritter[] = array(
                    'type' => 'success',
                    'title' => 'Success',
                    'message' => 'Album was successfully edited.');
            }else{
                $gritter[] = array(
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => $validator->messages()->first());
            }
        }
        return Redirect::back()->with(array('gritter' => $gritter));
	}


	public function destroy($album_id)
	{
        $album = Album::find($album_id);
        $this->canAccess('admin', true, $album->user_id);

        if($album === null)
        {
            App::abort(404);
        }

        $photos = $album->photos;

        if(!empty($photos))
        {
            $photos_controller = new \PhotosController();
            foreach($photos as $photo)
            {
                $photos_controller->destroy($photo->id);
            }
        }

        Album::destroy($album_id);

        return Redirect::back();
	}

    public function postComment()
    {

        $validator = Validator::make(
            Input::get(),
            array(
                'action' => 'required',
                'd' => 'required|integer'
            )
        );
        if(!$validator->fails())
        {
            $album = Album::find(Input::get('id'));

            if(Input::get('action') == 'increment')
                $album->increment('no_comments');
            if(Input::get('action') == 'decrement')
                $album->decrement('no_comments');
        }
    }

    public function postLike()
    {

        $validator = Validator::make(
            Input::get(),
            array(
                'action' => 'required',
                'id' => 'required|integer'
            )
        );
        if(!$validator->fails())
        {
            $album = Album::find(Input::get('id'));

            if(Input::get('action') == 'increment')
                $album->increment('no_likes');
            if(Input::get('action') == 'decrement')
                $album->decrement('no_likes');
        }
    }
}