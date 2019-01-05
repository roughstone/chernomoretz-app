<?php

namespace App\Http\Controllers;

use App\Sliders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// import the Intervention Image Manager Class
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'description' => ['required', 'min:6',],
        ]);

        if ($request->photo) {
            $fileName = time().'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            Image::make($request->photo)->save(public_path('/storage/sliders/'.$fileName));
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
     * @param  \App\Sliders  $slider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return Sliders::all()->where('id','=',$id);
        return Sliders::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sliders  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sliders  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $slider = Sliders::findOrfail($id);

        request()->validate([
            'title' => ['required', 'min:3'],
            'photos' => ['required', 'min:3'],
            'description' => ['required', 'min:6',],
        ]);

        $slider->update([
            'title' => $request['title'],
            'photos' => $request['photos'],
            'description' => $request['description'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sliders  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $slider = Sliders::findOrfail($id)->delete();

        unlink('../storage/app/public/slider/' . $slider->photos);

    }
}
