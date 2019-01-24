<?php

namespace App\Http\Controllers\API;

use App\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class NewsController extends Controller
{
    /**
     * Display a listing DB records table coachs based on category and paginate the records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return News::where('category', '=', 'announcement')->latest()->get();
    }

    public function moreNewsIndex()
    {
        return News::where('category', '=', 'news')->latest()->paginate(5);
    }

    /**
     * Store a newly created validated record in DB news table.
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
                'category' => ['required', 'min:4',],
                'date' => ['required', 'date',],
            ]);

            if ($request->photos) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/images/'.$fileName));
            }
            $object = new News();

            $object->title = request('title');
            $object->description = request('description');
            $object->photos = $fileName;
            $object->category = request('category');
            $object->date = request('date');

            $object->save();
        }
    }

    /**
     * Update the specified DB record in coachs table.
     * If there is a new base64 file delete the old one and store the new one
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        if (\Gate::allows('admin')) {
            $object = News::all()->where('id','=',$news->id)->first();

            request()->validate([
                'title' => ['required', 'min:3'],
                'photos' => ['required'],
                'description' => ['required', 'min:6',],
                'category' => ['required', 'min:3',],
                'date' => ['required', 'min:6',],
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
                'category' => $request['category'],
                'date' => $request['date']
            ]);
        }
    }

    /**
     * Remove the specified record from DB coachs table.
     * Delete the specified file.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        if (\Gate::allows('admin')) {

            $news->delete();

            unlink('../public/storage/images/' . $news->photos);
        }
    }
}
