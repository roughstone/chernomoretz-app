<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;

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

            $fileName = time().substr($request->photo, strrpos($request->photo, "."));

            if($request->method == 'rotate') {
                $img->rotate(-90);
            }
            if($request->method == 'crop') {
                $img->crop(
                    round($request->croperWidth),
                    round($request->croperHeight),
                    round($request->croperLeft),
                    round($request->croperTop)
                    );
            }
            if($request->method == 'flipX') {
                $img->flip();
            }
            if($request->method == 'flipY') {
                $img->flip('v');
            }
            unlink("../public/storage/images/".$request->photo);
            $img->save(public_path('/storage/images/' . $fileName));
            if($request->category == 'slider') {
               DB::table('sliders')->where('photos', $request->photo)->update(['photos' => $fileName]);
            }
            if($request->category == 'news') {
                DB::table('news')->where('photos', $request->photo)->update(['photos' => $fileName]);
            }
            if($request->category == 'coachs') {
                DB::table('coachs')->where('photos', $request->photo)->update(['photos' => $fileName]);
            }
            if($request->category == 'competitors') {
                DB::table('competitors')->where('photos', $request->photo)->update(['photos' => $fileName]);
            }
            if($request->category == 'galleries') {
                DB::table('galleries')->where('photos', $request->photo)->update(['photos' => $fileName]);
            }
            if($request->category == 'photos') {
                DB::table('photos')->where('photos', $request->photo)->update(['photos' => $fileName]);
            }
            return $fileName;
        }
    }
}
