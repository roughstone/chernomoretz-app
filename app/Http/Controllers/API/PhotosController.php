<?php

namespace App\Http\Controllers\API;

use App\Gallery;
use App\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class PhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Gallery  $photo
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $gallery = Gallery::where('id', $id)->first();
        $photos = Photo::where('gallery_id', $id)->get();
        $combined = $photos->prepend($gallery);

        return $combined;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        if (\Gate::allows('admin')) {
            request()->validate([
                'photos' => ['required', 'min:100'],
            ]);

            $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

            Image::make($request->photos)->save(public_path('/storage/galleries/'.$fileName));

            $object = new Photo();

            $object->title = request('title');
            $object->photos = $fileName;
            $object->gallery_id = $id;

            $object->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        if (\Gate::allows('admin')) {

            $photo->delete();

            unlink('../public/storage/galleries/' . $photo->photos);
        }
    }
}
