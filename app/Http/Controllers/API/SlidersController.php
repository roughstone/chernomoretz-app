<?php

namespace App\Http\Controllers\API;

use App\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;



class SlidersController extends Controller
{

    /**
    * Display a listing DB records table sliders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            return Slider::all();
    }

    /**
     * Store a newly created validated record in DB sliders table.
     * Convert a base64 file to an image and store it.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Gate::allows('admin')) {
            request()->validate([
                'title' => ['required', 'min:3'],
                'photos' => ['required'],
                'description' => ['required', 'min:6',],
            ]);

            if ($request->photos) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/images/'.$fileName));
            }
            $object = new Slider();

            $object->title = request('title');
            $object->description = request('description');
            $object->photos = $fileName;

            $object->save();
        }
    }

    /**
     * Update the specified DB record in sliders table.
     * If there is a new base64 file delete the old one and store the new one
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slider  $sliders
     * @return \Illuminate\Http\Response
        */
        public function update(Request $request, Slider $slider)
    {
        if (\Gate::allows('admin')) {
            $object = Slider::all()->where('id','=',$slider->id)->first();

            request()->validate([
                'title' => ['required', 'min:3'],
                'photos' => ['required'],
                'description' => ['required', 'min:6',],
            ]);

            if (strlen($request->photos) > 20) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/images/'.$fileName));
                unlink('../public/storage/images/' . $object->photos);
            } else {
                $fileName = $object->photos;
            }

            $object->update([
                'title' => $request['title'],
                'photos' => $fileName,
                'description' => $request['description'],
            ]);
        }
    }

    /**
     * Remove the specified record from DB sliders table.
     * Delete the specified file.
     *
     * @param  \App\Slider  $sliders
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        if (\Gate::allows('admin')) {

            $Slider->delete();

            unlink('../public/storage/images/' . $slider->photos);
        }
    }
}
