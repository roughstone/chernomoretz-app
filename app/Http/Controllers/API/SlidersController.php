<?php

namespace App\Http\Controllers\API;

use App\Sliders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class SlidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Sliders::paginate(25);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => ['required', 'min:3'],
            'photos' => ['required'],
            'description' => ['required', 'min:6',],
        ]);

        if ($request->photos) {
            $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

            Image::make($request->photos)->save(public_path('/storage/sliders/'.$fileName));
        }
        $object = new Sliders();

        $object->title = request('title');
        $object->description = request('description');
        $object->photos = $fileName;

        $object->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sliders  $sliders
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Sliders::all()->where('id','=',$id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sliders  $sliders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $slider = Sliders::findOrfail($id);

        request()->validate([
            'title' => ['required', 'min:3'],
            'photos' => ['required'],
            'description' => ['required', 'min:6',],
        ]);

        if ($request->photos) {
            $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

            Image::make($request->photos)->save(public_path('/storage/sliders/'.$fileName));
            unlink('../storage/app/public/sliders/' . $slider->photos);
        } else {
            $fileName = $slider->photos;
        }

        $slider->update([
            'title' => $request['title'],
            'photos' => $fileName,
            'description' => $request['description'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sliders  $sliders
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $slider = Sliders::findOrfail($id)->delete();

        unlink('../storage/app/public/sliders/' . $slider->photos);

    }
}
