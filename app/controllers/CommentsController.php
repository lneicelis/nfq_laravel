<?php

class CommentsController extends \BaseController
{

    /**
     *
     */
    public function postComment()
    {
        $validator = Validator::make(
            Input::get(),
            array(
                'type' => 'required',
                'obj_id' => 'required',
                'comment' => 'required'
            )
        );
        if(!$validator->fails())
        {
            /**
             * insert into `comments` (`type`, `obj_id`, `user_id`, `comment`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?)
             */
            Comment::create(array(
                'type' => Input::get('type'),
                'obj_id' => Input::get('obj_id'),
                'user_id' => Sentry::getUser()->id,
                'comment' => e(Input::get('comment')),
            ));
            if(Input::get('type') == 'photo'){
                $photo = Photo::find(Input::get('obj_id'));
                $photo->increment('no_comments');
            }
        }else{
            return Response::make('Not all necessary field are filled');
        }
    }

    /**
     * @return mixed
     */
    public function postShowComments()
    {
        $validator = Validator::make(
            Input::get(),
            array(
                'type' => 'required',
                'obj_id' => 'required'
            )
        );
        if(!$validator->fails())
        {
            /**
             * select `users`.`first_name`, `users`.`last_name`, `users`.`id`, `users_info`.`picture`, `comments`.`comment`, `comments`.`created_at` from `comments` inner join `users` on `users`.`id` = `comments`.`user_id` inner join `users_info` on `users_info`.`id` = `comments`.`user_id` where `type` = ? and `obj_id` = ? order by `comments`.`created_at` desc
             */
            $comments = DB::table('comments')
                ->select('users.first_name', 'users.last_name', 'users.id', 'users_info.picture', 'comments.comment', 'comments.created_at')
                ->where('type', '=', Input::get('type'))
                ->where('obj_id', '=', Input::get('obj_id'))
                ->join('users', 'users.id', '=', 'comments.user_id')
                ->join('users_info', 'users_info.id', '=', 'comments.user_id')
                ->orderBy('comments.created_at', 'DESC')
                ->get();

            return Response::json($comments, 200);
        }else{
            return Response::json('', 404);
        }
    }
} 