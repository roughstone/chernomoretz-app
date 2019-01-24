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
     * Display a listing DB records table coachs based on category,
     * reverse the records and paginate them.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Gallery::where('category', '=', $request->category)->latest()->paginate(9);
    }

    /**
     * Store a newly created validated record in DB galleries table.
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
                'date' => ['required', 'date'],
                'category' => ['required', 'min:3'],
                'photos' => ['required', 'min:3'],
            ]);

            if ($request->photos) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/images/'.$fileName));
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
     * Update the specified DB record in galleries table.
     * If there is a new base64 file delete the old one and store the new one
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

                Image::make($request->photos)->save(public_path('/storage/images/'.$fileName));
                unlink('../public/storage/images/' . $object->photos);
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
     * Remove the specified record from DB galleries table.
     * Delete the specified file.
     *
     * @param  \App\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        if (\Gate::allows('admin')) {

            foreach($gallery->galleryPhotos as $photo) {
                unlink('../public/storage/images/' . $photo->photos);
                $photo->delete();
            }

            unlink('../public/storage/images/' . $gallery->photos);
            $gallery->delete();
        }
    }
}
