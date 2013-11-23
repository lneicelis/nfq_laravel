<?php
/**
 * Created by PhpStorm.
 * User: Luko
 * Date: 13.11.9
 * Time: 19.02
 */

namespace Luknei\Gallery;

class Gallery {

    protected $thumb_width = 200;
    protected $thumb_height = 200;

    protected $photo_max_width = 640;
    protected $photo_max_height = 640;

    protected $thumb_path = 'gallery/thumbs/';
    protected $photo_path = 'gallery/images/';

    public function thumbnail($open, $save_name)
    {

        $save = public_path($this->thumb_path . $save_name);

        try{
            $imagine = new \Imagine\Gd\Imagine();

            $crop_size = new \Imagine\Image\Box($this->thumb_width, $this->thumb_height);

            $mode_crop = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

            $imagine->open($open)
                ->thumbnail($crop_size, $mode_crop)
                ->save($save);

            return TRUE;
        }
        catch(\Imagine\Exception\Exception $e)
        {
            return FALSE;
        }


    }

    public function image($open, $save_name)
    {

        $save = public_path($this->photo_path . $save_name);

        try{
            $imagine = new \Imagine\Gd\Imagine();

            $resize_size = new \Imagine\Image\Box($this->photo_max_width, 20000);

            if($imagine->open($open)->getSize()->getWidth() < $imagine->open($open)->getSize()->getHeight())
            {
                $resize_size = new \Imagine\Image\Box(20000, $this->photo_max_height);
            }

            $mode_resize = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;

            $imagine->open($open)
                ->thumbnail($resize_size, $mode_resize)
                ->save($save);

            return TRUE;
        }
        catch(\Imagine\Exception\Exception $e)
        {
            return FALSE;
        }
    }

    public function crop($open_name, $save_name, $x, $y, $width, $height)
    {
        $imagine = new \Imagine\Gd\Imagine();

        $open = public_path($this->photo_path . $open_name);

        $save = public_path($this->photo_path . $save_name);

        try{
            $imagine->open($open)
                ->crop(new \Imagine\Image\Point($x, $y), new \Imagine\Image\Box($width, $height))
                ->save($save);

            $this->thumbnail($save, $save_name);

            return TRUE;
        }
        catch(\Imagine\Exception\Exception $e){
            return FALSE;
        }
    }

    public function rotate($open_name, $save_name, $rotate)
    {
        $imagine = new \Imagine\Gd\Imagine();

        $open = public_path($this->photo_path . $open_name);

        $save = public_path($this->photo_path . $save_name);

        try{
            $imagine->open($open)
                ->rotate($rotate)
                ->save($save);

            $this->thumbnail($save, $save_name);

            return TRUE;
        }
        catch(\Imagine\Exception\Exception $e){
            return FALSE;
        }
    }
} 