<?php

namespace App\Http\Controllers\API;

use App\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return News::paginate(10);
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
                'photos' => ['required'],
                'description' => ['required', 'min:6',],
                'category' => ['required', 'min:6',],
                'date' => ['required', 'min:6',],
            ]);

            if ($request->photos) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/news/'.$fileName));
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
     * Display the specified resource.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return News::all()->where('id','=',$news->id);
    }

    /**
     * Update the specified resource in storage.
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
                'category' => ['required', 'min:6',],
                'date' => ['required', 'min:6',],
            ]);

            if (strlen($request->photos) > 20) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/news/'.$fileName));
                unlink('../public/storage/news/' . $object->photos);
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
     * Remove the specified resource from storage.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        if (\Gate::allows('admin')) {

            $news->delete();

            unlink('../public/storage/news/' . $news->photos);
        }
    }
}
