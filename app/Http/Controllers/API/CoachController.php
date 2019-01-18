<?php

namespace App\Http\Controllers\API;

use App\Coach;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class CoachController extends Controller
{
    /**
     * Display a listing DB records table coachs and paginate the records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Coach::paginate(5);
    }

    /**
     * Store a newly created validated record in DB coachs table.
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

                Image::make($request->photos)->save(public_path('/storage/coachs/'.$fileName));
            }
            $object = new Coach();

            $object->firstName = request('firstName');
            $object->lastName = request('lastName');
            $object->photos = $fileName;
            $object->birthday = request('birthday');
            $object->position = request('position');
            $object->description = request('description');

            $object->save();
        }
    }

    /**
     * Update the specified DB record in coachs table.
     * If there is a new base64 file delete the old one and store the new one
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
                'firstName' => ['required', 'min:3'],
                'lastName' => ['required', 'min:3'],
                'photos' => ['required', 'min:3'],
                'birthday' => ['required', 'date'],
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
                'firstName' => $request['firstName'],
                'lastName' => $request['lastName'],
                'photos' => $fileName,
                'birthday' => $request['birthday'],
                'description' => $request['description'],
                'position' => $request['position'],
            ]);
        }
    }

    /**
     * Remove the specified record from DB coachs table.
     * Delete the specified file.
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
