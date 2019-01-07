<?php

namespace App\Http\Controllers\API;

use App\Coach;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Coach::paginate(25);
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
            ]);

            if ($request->photos) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/coachs/'.$fileName));
            }
            $object = new Coach();

            $object->title = request('title');
            $object->description = request('description');
            $object->photos = $fileName;

            $object->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coach  $coach
     * @return \Illuminate\Http\Response
     */
    public function show(Coach $coach)
    {
        return Coach::all()->where('id','=',$coach->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coach  $coach
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coach $coach)
    {
        if (\Gate::allows('admin')) {
            $object = Coach::all()->where('id','=',$coach->id)->first();

            request()->validate([
                'title' => ['required', 'min:3'],
                'photos' => ['required'],
                'description' => ['required', 'min:6',],
            ]);

            if (strlen($request->photos) > 20) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/coachs/'.$fileName));
                unlink('../public/storage/coachs/' . $object->photos);
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Coach  $coach
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coach $coach)
    {
        if (\Gate::allows('admin')) {

            $coach->delete();

            unlink('../public/storage/coachs/' . $coach->photos);
        }
    }
}
