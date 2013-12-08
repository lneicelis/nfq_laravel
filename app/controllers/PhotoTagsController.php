<?php
class PhotoTagsController extends \BaseController
{

    /**
     * @return mixed
     */
    public function postGet()
    {
        if (Input::has('photo_id')) {
            /**
             * select `id` as `tag-id`, `title` as `tag-title`, `description` as `tag-description`, `color` as `tag-color`, `size` as `tag-size`, `url`, `x`, `y` from `photo_tags` where `photo_id` = ?
             */
            $tags = PhotoTag::where('photo_id', '=', Input::get('photo_id'))
                ->get(array('id as tag-id', 'title as tag-title', 'description as tag-description', 'color as tag-color', 'size as tag-size', 'url', 'x', 'y'));

            return Response::json($tags, 200);
        }
    }

    /**
     * @return mixed
     */
    public function postCreate()
    {
        $tag = Input::Get();
        $validator = Validator::make(
            $tag,
            array(
                'photo-id' => 'required|integer',
                'tag-title' => 'required',
                'x' => 'required',
                'y' => 'required'
            )
        );

        if (!$validator->fails()) {
            /**
             * insert into `photo_tags` (`photo_id`, `title`, `description`, `url`, `color`, `size`, `x`, `y`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
             */
            $photo_tag = new PhotoTag();
            $photo_tag->photo_id = $tag['photo-id'];
            $photo_tag->title = e($tag['tag-title']);
            $photo_tag->description = e($tag['tag-description']);
            $photo_tag->url = e($tag['tag-url']);
            $photo_tag->color = $tag['tag-color'];
            $photo_tag->size = $tag['tag-size'];
            $photo_tag->x = $tag['x'];
            $photo_tag->y = $tag['y'];
            $photo_tag->save();
            /**
             * update `photos` set `no_tags` = `no_tags` + 1, `updated_at` = ? where `id` = ?
             */
            $photo_tag->photo->increment('no_tags');

            $tag['tag-id'] = $photo_tag->id;

            return Response::json(Input::get(), 200);
        } else {

            $error = $validator->messages()->first();
            return Response::json($error, 404);
        }
    }

    /**
     * @return mixed
     */
    public function postEdit()
    {

        $tag = Input::Get();
        $validator = Validator::make(
            $tag,
            array(
                'photo-id' => 'required|integer',
                'tag-title' => 'required',
                'x' => 'required',
                'y' => 'required'
            )
        );

        if (!$validator->fails()) {
            /**
             * update `photo_tags` set `description` = ?, `url` = ?, `x` = ?, `y` = ?, `updated_at` = ? where `id` = ?
             */
            $photo_tag = PhotoTag::find($tag['tag-id']);
            $photo_tag->title = e($tag['tag-title']);
            $photo_tag->description = e($tag['tag-description']);
            $photo_tag->url = e($tag['tag-url']);
            $photo_tag->color = $tag['tag-color'];
            $photo_tag->size = $tag['tag-size'];
            $photo_tag->x = $tag['x'];
            $photo_tag->y = $tag['y'];
            $photo_tag->save();

            return Response::json($tag, 200);
        } else {

            $error = $validator->messages()->first();
            return Response::json($error, 404);
        }
    }

    /**
     * @return mixed
     */
    public function postDelete()
    {
        /**
         * select * from `photo_tags` where `id` = ? limit 1
         */
        $tag = PhotoTag::find(Input::get('tag-id'));
        /**
         * delete from `photo_tags` where `id` = ?
         */
        $affected_rows = $tag->delete();
        /**
         * update `photos` set `no_tags` = `no_tags` - 1, `updated_at` = ? where `id` = ?
         */
        $tag->photo->decrement('no_tags');

        if ($affected_rows) {
            return Response::json("Tag was successfully deleted.", 200);
        } else {
            return Response::json("Tag was not deleted.", 404);
        }

    }
} 