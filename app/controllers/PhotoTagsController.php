<?php
class PhotoTagsController extends \BaseController {

    public function postGet()
    {
        if(Input::has('photo_id'))
        {
            $tags = PhotoTag::where('photo_id', '=',Input::get('photo_id'))
                ->get(array('id as tag-id', 'title as tag-title', 'description as tag-description','url', 'x', 'y'));

            return Response::json($tags, 200);
        }
    }

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

        if(!$validator->fails())
        {
            $photo_tag = new PhotoTag();
            $photo_tag->photo_id = $tag['photo-id'];
            $photo_tag->title = $tag['tag-title'];
            $photo_tag->description = $tag['tag-description'];
            $photo_tag->x = $tag['x'];
            $photo_tag->y = $tag['y'];
            $photo_tag->save();

            $tag['tag-id'] = $photo_tag->id;

            return Response::json(Input::get(), 200);
        }else{

            $error = $validator->messages()->first();
            return Response::json($error,404);
        }
    }

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

        if(!$validator->fails())
        {
            $photo_tag = PhotoTag::find($tag['tag-id']);
            $photo_tag->photo_id = $tag['photo-id'];
            $photo_tag->title = $tag['tag-title'];
            $photo_tag->description = $tag['tag-description'];
            $photo_tag->x = $tag['x'];
            $photo_tag->y = $tag['y'];
            $photo_tag->save();

            return Response::json($tag, 200);
        }else{

            $error = $validator->messages()->first();
            return Response::json($error,404);
        }
    }
    public function postDelete()
    {
        $tag = PhotoTag::find(Input::get('tag-id'));

        $affected_rows = $tag->delete();

        if($affected_rows){
            return Response::json("Tag was successfully deleted.", 200);
        }else{
            return Response::json("Tag was not deleted.", 404);
        }

    }
} 