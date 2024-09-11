<?php

namespace App\Trait;

trait ImageTrait
{
    public function storimage($image, $pathi)
    {
        $photo = $image->getClientOriginalExtension();
        $photo_name = $pathi.'/'.time().'.'.$photo;
        $image->move($pathi, $photo_name);
        return $photo_name;
    }
}
