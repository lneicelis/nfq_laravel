<?php

class AlbumsController extends \BaseController {

    /**
     * @param null $user_id
     * @return mixed
     */
    public function index($user_id = null)
	{

        if($user_id === null)
            $user_id = Sentry::getUser()->id;

        $response['default_cover'] = 'default.jpg';
        $response['can_edit'] = $this->canAccess('admin', false, $user_id);
        /**
         * select `albums`.`id`, `albums`.`user_id`, `albums`.`title`, `albums`.`description`, `albums`.`no_photos`, `albums`.`no_comments`, `albums`.`no_likes`, `photos`.`file_name` from `albums`
         * left join `photos` on `albums`.`cover_photo` = `photos`.`id`
         * where `albums`.`user_id` = ?
         * limit 15
         */
        $response['albums'] = $albums = DB::table('albums')
            ->leftJoin('photos', 'albums.cover_photo', '=', 'photos.id')
            ->where('albums.user_id', '=', $user_id)
            ->select('albums.id', 'albums.user_id', 'albums.title', 'albums.description', 'albums.no_photos', 'albums.no_comments', 'albums.no_likes', 'photos.file_name')
            ->paginate(15);

        if(count($albums) == 0)
        {
            $response['alerts'][] = array(
                'type' => 'info',
                'title' => 'Info',
                'message' => 'There are no albums in the gallery.');
        }
        Breadcrumbs::addCrumb('Gallery', URL::action('AlbumsController@index'));

        return View::make('admin.gallery.albums-list', $response);
	}


    /**
     * @return mixed
     */
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

            /**
             * insert into `albums` (`user_id`, `title`, `description`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?)
             */
            Album::create(array(
                'user_id' => $user_id,
                'title' => e(Input::get('title')),
                'description' => e(Input::get('description'))
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

    /**
     * @return mixed
     */
    public function postSetCover()
	{
        /**
         * select * from `photos` where `id` = ? limit 1
         */
        $photo = Photo::find(Input::get('id'));
        $this->canAccess('admin', true, $photo->album->user_id);

        if(!empty($photo)){
            /**
             * select * from `albums` where `albums`.`id` = ? limit 1
             * update `albums` set `cover_photo` = ?, `updated_at` = ? where `id` = ?
             */
            $photo->album->update(array('cover_photo' => $photo->id));

            $gritter = array(
                'type' => 'success',
                'title' => 'Success',
                'message' => 'Cover photo have been changed.');
            return Response::json($gritter, 200);
        }

        return Response::json(404);
	}

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
	{
        $user = Sentry::getUser();
        /**
         * select * from `albums` where `id` = ? limit 1
         */
        $response['album'] = $album = Album::find($id);
        /**
         * select * from `albums` where `id` != ? and `user_id` = ?
         */
        $response['albums'] = $albums = Album::where('id', '!=', $id)
            ->where('user_id', '=', $user->id)->get();
        /**
         * select * from `photos` where `album_id` = ? limit 15 offset 0
         */
        $response['photos'] = Photo::where('album_id', '=', $album->id)->paginate(15);
        /**
         * update `albums` set `no_views` = `no_views` + 1, `updated_at` = ? where `id` = ?
         */
        $album->increment('no_views');

        $response['can_edit'] = $this->canAccess('admin', false, $album->user_id);

        if($album === null)
        {
            App::abort(404);
        }

        if($album->photos->count() === 0)
        {
            $response['alerts'][] = array(
                'type' => 'info',
                'title' => 'Info',
                'message' => 'The album is empty.');
        }

        Breadcrumbs::addCrumb('Gallery', URL::action('AlbumsController@index', array('user_id' => $album->user_id)));
        Breadcrumbs::addCrumb($album->title . ' album');

        return View::make('admin.gallery.photos-list', $response);
	}

    /**
     * @return mixed
     */
    public function postEdit()
	{

		$album_id = Input::get('album_id');
		$title = Input::get('title');
		$description = Input::get('description');
        /**
         * select * from `albums` where `id` = ? limit 1
         */
        $album = Album::where('id', '=', $album_id)->first();
        $this->canAccess('admin', true, $album->user_id);

        if(!empty($album))
        {
            $validator = Validator::make(
                array(
                    'title' => e($title),
                    'description' => e($description),
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
                /**
                 * update `albums` set `title` = ?, `updated_at` = ? where `id` = ?
                 */
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


    /**
     * @param $album_id
     * @return mixed
     */
    public function destroy($album_id)
	{
        /**
         * select * from `albums` where `id` = ? limit 1
         */
        $album = Album::find($album_id);
        $this->canAccess('admin', true, $album->user_id);

        $photos = $album->photos;

        if(!empty($photos))
        {
            $photos_controller = new \PhotosController();
            foreach($photos as $photo)
            {
                $photos_controller->destroy($photo->id);
            }
        }
        /**
         * delete from `albums` where `id` = ?
         */
        Album::destroy($album_id);
        /**
         * delete from `likes` where `type` = ? and `obj_id` = ?
         */
        Like::where('type', '=', 'album')->where('obj_id', '=', $album->id)->delete();

        return Redirect::back();
	}
}