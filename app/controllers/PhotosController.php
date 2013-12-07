<?php

class PhotosController extends \BaseController {

    /**
     * @param $album_id
     * @return mixed
     */
    public function getUpload($album_id)
    {
        /**
         * select * from `albums` where `id` = ? limit 1
         */
        $album = Album::find($album_id);
        $this->canAccess('admin', true, $album->user_id);

        $response['album_id'] = $album->id;

        Breadcrumbs::addCrumb('Gallery', URL::action('AlbumsController@index', array('user_id' => $album->user_id)));
        Breadcrumbs::addCrumb($album->title . ' album', URL::action('AlbumsController@show', array('id' => $album_id)));
        Breadcrumbs::addCrumb('Upload photos');

        return View::make('admin.gallery.photos-upload-form', $response);
    }

    /**
     * @param $album_id
     * @return mixed
     */
    public function postUpload($album_id)
    {
        $album = Album::find($album_id);
        $this->canAccess('admin', true, $album->user_id);

        if (Input::hasFile('file'))
        {
            $file = Input::file('file');
            $size = (integer)Setting::findSettings('gallery', 'max_file_size');
            $mimes = Setting::findSettings('gallery', 'mimes');
            $restrictions = 'mimes:' . $mimes . '|max:' . $size;
            $validator = Validator::make(
                array('file' => $file),
                array('file' => $restrictions)
            );

            if ($validator->fails())
            {
                return Response::make($validator->messages()->first(), 400);
            }
            else
            {
                $new_file_name = str_random(16) . '.' . $file->getClientOriginalExtension();

                $tmp_file = $file->getRealPath();

                $create_thumb = Gallery::thumbnail($tmp_file, $new_file_name);

                $create_image = Gallery::image($tmp_file, $new_file_name, 800, 800);

                if($create_thumb && $create_image)
                {
                    $description = substr($file->getClientOriginalName(), 0, strlen($file->getClientOriginalName()) - strlen($file->getClientOriginalExtension()) - 1);
                    Photo::create(array(
                        'album_id' => $album_id,
                        'description' => e($description),
                        'file_name' => $new_file_name));

                    Album::find($album_id)->increment('no_photos');

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

                    return Response::json($alerts, 404);
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function edit()
	{
        /**
         * select * from `photos` where `id` = ? limit 1
         */
        $photo = Photo::find(Input::get('photo_id'));
        $this->canAccess('admin', true, $photo->album->user_id);

        $validator = Validator::make(
            Input::get(),
            array(
                'description' => 'max:255'
            ),
            array(
                'required' => 'Enter description, please.'
            )
        );
        if(!$validator->fails()){
            /**
             * update `photos` set `description` = ?, `updated_at` = ? where `id` = ?
             */
            $photo->description = e(Input::get('description'));
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

    /**
     * @param $photo_id
     * @return mixed
     */
    public function destroy($photo_id)
	{
        $photo = Photo::find($photo_id);

        //check if this user can access this function => admin or owner
        $this->canAccess('admin', true, $photo->album->user_id);

        //delete actual files on server
        unlink(public_path('public_gallery/images/' . $photo->file_name));
        unlink(public_path('public_gallery/thumbs/' . $photo->file_name));

        //delete photo and its tags records in database
        /**
         * delete from `photos` where `id` = ?
         */
        Photo::destroy($photo->id);
        /**
         * delete from `photo_tags` where `photo_id` = ?
         */
        PhotoTag::where('photo_id', '=', $photo->id)->delete();
        /**
         * delete from `likes` where `type` = ? and `obj_id` = ?
         */
        Like::where('type', '=', 'photo')->where('obj_id', '=', $photo->id)->delete();
        /**
         * delete from `comments` where `type` = ? and `obj_id` = ?
         */
        Comment::where('type', '=', 'photo')->where('obj_id', '=', $photo->id)->delete();
        /**
         * decrease the number of photos in the album
         * update `albums` set `no_photos` = `no_photos` - 1, `updated_at` = ? where `id` = ?
         */
        $photo->album->decrement('no_photos');

        $gritter = array(
            'type' => 'success',
            'title' => 'Message',
            'message' => 'Photo has been successfully deleted.');

        return Response::json($gritter, 200);


	}

    /**
     * @return mixed
     */
    public function getPhotos(){

        $id = Input::get('id');
        /**
         * select * from `albums` where `id` = ? limit 1
         * select * from `photos` where `photos`.`album_id` = ?
         */
        $photos = Album::find($id)->photos;

        return Response::json($photos, 200);
    }

    /**
     * @return mixed
     */
    public function postTransfer()
    {
        $album = Album::find(Input::get('album_id'));
        $photo = Photo::find(Input::get('photo_id'));

        //check if this user can access this function => admin or owner
        $this->canAccess('admin', true, $album->user_id);
        $this->canAccess('admin', true, $photo->album->user_id);
        /**
         * update `albums` set `no_photos` = `no_photos` - 1, `updated_at` = ? where `id` = ?
         */
        $photo->album->decrement('no_photos');
        /**
         * update `albums` set `no_photos` = `no_photos` + 1, `updated_at` = ? where `id` = ?
         */
        $album->increment('no_photos');
        /**
         * update `photos` set `album_id` = '3', `updated_at` = '2013-12-06 10:53:28' where `id` = '8'
         */
        $affectedRows = Photo::where('id', '=', $photo->id)->update(array('album_id' => $album->id));

        if($affectedRows > 0){
            return Response::json(200);
        }else{
            Return response::json(404);
        }
    }

    /**
     * @return mixed
     */
    public function postCrop()
    {
        /**
         * select * from `photos` where `id` = ? limit 1
         */
        $photo = Photo::find(Input::get('photo-id'));

        //check if this user can access this function => admin or owner
        $this->canAccess('admin', true, $photo->album->user_id);

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
            if(!empty($photo))
            {
                $new_file_name = str_random(16) . substr($photo->file_name, 16);

                if(Gallery::crop($photo->file_name, $new_file_name, $x, $y, $w, $h)){
                    /**
                     * insert into `photos` (`album_id`, `description`, `file_name`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?)
                     */
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

    /**
     * @param $direction
     * @return mixed
     */
    public function postRotate($direction)
    {
        $rotate = ($direction === "left") ? 270 : 90;
        /**
         * select * from `photos` where `id` = ? limit 1
         */
        $photo = Photo::find(Input::get('id'));

        //check if this user can access this function => admin or owner
        $this->canAccess('admin', true, $photo->album->user_id);

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

    public function postGetPhotoInfo()
    {
        if(Input::has('id'))
        {
            $photo = Photo::find(Input::get('id'));
            /**
             * update `photos` set `no_views` = `no_views` + 1, `updated_at` = ? where `id` = ?
             */
            $photo->increment('no_views');
            /**
             * select * from `photos` where `id` = ? limit 1
             */
            $photoArray = $photo->toArray();
            /**
             * select `users`.`id`, `users`.`first_name`, `users`.`last_name`, `users_info`.`picture` from `users` inner join `users_info` on `users_info`.`user_id` = `users`.`id` where `users`.`id` = ? limit 1
             */
            $userArray = User::where('users.id', '=', $photo->album->user->id)
                ->select('users.id', 'users.first_name', 'users.last_name', 'users_info.picture')
                ->join('users_info', 'users_info.user_id', '=', 'users.id')
                ->first()->toArray();

            return Response::json(array('photo' => $photoArray, 'user' => $userArray), 200);
        }
    }

    /**
     * @return mixed
     */
    public function postStatus()
    {
        /**
         * select * from `photos` where `id` = ? limit 1
         */
        $photo = Photo::find(Input::get('id'));

        //check if this user can access this function => admin or owner
        $this->canAccess('admin', true, $photo->album->user_id);

        if(!empty($photo->id))
        {

            $status = ($photo->status === 1) ? 0 : 1;
            /**
             * update `photos` set `status` = ?, `updated_at` = ? where `id` = ?
             */
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