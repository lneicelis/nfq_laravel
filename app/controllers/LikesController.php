<?php

class LikesController extends \BaseController
{

    public function postGetLikes()
    {
        $validator = Validator::make(
            Input::get(),
            array(
                'type' => 'required',
                'obj_id' => 'required',
            )
        );
        if (!$validator->fails()) {

            $likes = Like::where('type', '=', Input::get('type'))
                ->where('obj_id', '=', Input::get('obj_id'));
            /**
             * select count(*) as aggregate from `likes` where `type` = ? and `obj_id` = ?
             */
            $response['no_likes'] = $likes->count();
            /**
             * select count(*) as aggregate from `likes` where `type` = 'album' and `obj_id` = ? and `user_id` = ?
             */
            $response['user_likes'] = $likes->where('user_id', '=', Sentry::getUser()->id)->count();

            return Response::json($response, 200);
        }
    }

    public function postLike()
    {
        $validator = Validator::make(
            Input::get(),
            array(
                'type' => 'required',
                'obj_id' => 'required',
            )
        );
        if (!$validator->fails()) {
            if (Input::get('type') == 'photo') {
                $this->likePhoto();
            }

            if (Input::get('type') == 'album') {
                $this->likeAlbum();
            }
        }

    }

    protected function likePhoto()
    {
        $user = Sentry::getUser();
        $photo = Photo::find(Input::get('obj_id'));
        $like = Like::where('type', '=', Input::get('type'))
            ->where('obj_id', '=', Input::get('obj_id'))
            ->where('user_id', '=', $user->id);

        if (count($like->get()) === 0) {
            /**
             * update `albums` set `no_likes` = `no_likes` + 1, `updated_at` = ? where `id` = ?
             */
            $photo->increment('no_likes');
            /**
             * insert into `likes` (`type`, `obj_id`, `user_id`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?)
             */
            Like::create(array(
                'type' => Input::get('type'),
                'obj_id' => Input::get('obj_id'),
                'user_id' => $user->id
            ));
        } else {
            /**
             * update `albums` set `no_likes` = `no_likes` - 1, `updated_at` = ? where `id` = ?
             */
            $photo->decrement('no_likes');
            /**
             * delete from `likes` where `type` = 'album' and ? and `user_id` = ?
             */
            $like->delete();
        }
    }

    protected function likeAlbum()
    {
        $user = Sentry::getUser();
        $album = Album::find(Input::get('obj_id'));
        $like = Like::where('type', '=', Input::get('type'))
            ->where('obj_id', '=', Input::get('obj_id'))
            ->where('user_id', '=', $user->id);

        if (count($like->get()) === 0) {
            /**
             * update `albums` set `no_likes` = `no_likes` + 1, `updated_at` = ? where `id` = ?
             */
            $album->increment('no_likes');
            /**
             * insert into `likes` (`type`, `obj_id`, `user_id`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?)
             */
            Like::create(array(
                'type' => Input::get('type'),
                'obj_id' => Input::get('obj_id'),
                'user_id' => $user->id
            ));
        } else {
            /**
             * update `albums` set `no_likes` = `no_likes` - 1, `updated_at` = ? where `id` = ?
             */
            $album->decrement('no_likes');
            /**
             * delete from `likes` where `type` = 'album' and ? and `user_id` = ?
             */
            $like->delete();
        }
    }
} 