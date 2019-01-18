<?php

namespace App\Http\Controllers\API;

use App\Competitor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class CompetitorsController extends Controller
{
    /**
     * Display a listing DB records table competitors and paginate the records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Competitor::paginate(9);
    }

    /**
     * Store a newly created validated record in DB competitors table.
     * Convert a base64 file to an image and store it.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Gate::allows('admin')) {
            request()->validate([
                'firstName' => ['required', 'min:3'],
                'lastName' => ['required', 'min:3'],
                'photos' => ['required', 'min:3'],
                'birthday' => ['required', 'date'],
                'description' => ['required', 'min:6',],
            ]);

            if ($request->photos) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/competitors/'.$fileName));
            }
            $object = new Competitor();

            $object->firstName = request('firstName');
            $object->lastName = request('lastName');
            $object->photos = $fileName;
            $object->birthday = request('birthday');
            $object->sport = request('sport');
            $object->description = request('description');

            $object->save();
        }
    }

    /**
     * Update the specified DB record in competitors table.
     * If there is a new base64 file delete the old one and store the new one
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Competitor  $competitor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Competitor $competitor)
    {
        if (\Gate::allows('admin')) {
            $object = Competitor::all()->where('id','=',$competitor->id)->first();

            request()->validate([
                'firstName' => ['required', 'min:3'],
                'lastName' => ['required', 'min:3'],
                'photos' => ['required', 'min:3'],
                'birthday' => ['required', 'date'],
                'description' => ['required', 'min:6',],
            ]);

            if (strlen($request->photos) > 20) {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];

                Image::make($request->photos)->save(public_path('/storage/competitors/'.$fileName));
                unlink('../public/storage/competitors/' . $object->photos);
            } else {
                $fileName = $object->photos;
            }

            $object->update([
                'firstName' => $request['firstName'],
                'lastName' => $request['lastName'],
                'photos' => $fileName,
                'birthday' => $request['birthday'],
                'description' => $request['description'],
                'sport' => $request['sport'],
            ]);
        }
    }

    /**
     * Remove the specified record from DB competitors table.
     * Delete the specified file.
     *
     * @param  \App\Competitor  $competitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Competitor $competitor)
    {
        if (\Gate::allows('admin')) {

            $competitor->delete();

            unlink('../public/storage/competitors/' . $competitor->photos);
        }
    }
}
