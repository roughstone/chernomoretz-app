<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class EditorController extends Controller
{
    /**
     * Edit file based on specific request method.
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        if (\Gate::allows('admin')) {
            $img = Image::make(public_path('/storage/images/' . $request->photo));
            if($request->method == 'rotate') {
                $img->rotate(-90)->save();
            }
            if($request->method == 'crop') {
                $img->crop(
                    round($request->croperWidth),
                    round($request->croperHeight),
                    round($request->croperLeft),
                    round($request->croperTop)
                    )->save();
            }
            if($request->method == 'flipX') {
                $img->flip()->save();
            }
            if($request->method == 'flipY') {
                $img->flip('v')->save();
            }
        }
    }
}
