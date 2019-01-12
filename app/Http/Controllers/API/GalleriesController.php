<?php

namespace App\Http\Controllers\API;

use App\Photo;
use App\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class GalleriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Gallery::where('category', '=', $request->category)->latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Gate::allows('admin')) {
            request()->validate([
                'title' => ['required', 'min:3'],
                'date' => ['required', 'date'],
                'category' => ['required', 'min:3'],
                'photos' => ['required', 'min:3'],
            ]);

            if ($request->photos) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/galleries/'.$fileName));
            }
            $object = new Gallery();

            $object->title = request('title');
            $object->date = request('date');
            $object->photos = $fileName;
            $object->category = request('category');
            $object->description = request('description');

            $object->save();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {
        if (\Gate::allows('admin')) {
            $object = Gallery::all()->where('id','=',$galleries->id)->first();

            request()->validate([
                'title' => ['required', 'min:3'],
                'date' => ['required', 'date'],
                'category' => ['required', 'min:3'],
                'photos' => ['required', 'min:3'],
            ]);

            if (strlen($request->photos) > 20) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/galleries/'.$fileName));
                unlink('../public/storage/galleries/' . $object->photos);
            } else {
                $fileName = $object->photos;
            }

            $object->update([
                'title' => $request['firstName'],
                'date' => $request['lastName'],
                'photos' => $fileName,
                'category' => $request['birthday'],
                'description' => $request['description'],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        if (\Gate::allows('admin')) {

            $gallery->delete();

            unlink('../public/storage/galleries/' . $gallery->photos);
        }
    }
}
