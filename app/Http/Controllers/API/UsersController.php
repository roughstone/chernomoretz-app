<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Gate::allows('admin')) {
            return User::latest()->paginate(10);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (\Gate::allows('admin') && $request['changeRole']) {
            $user->update([
                'role' => $request['changeRole'],
            ]);
        } else if ($user->id == auth()->id()) {
            if ($user->photo != 'incognito.png') {
                $fileName = time().'.'.explode('/', explode(':', substr($request->photos, 0, strpos($request->photos, ';')))[1])[1];
                $img = Image::make($request->photos)->resize(
                    $request->width,
                    $request->height)->crop(
                        round($request->croperWidth),
                        round($request->croperHeight),
                        round($request->croperLeft),
                        round($request->croperTop))
                        ->save(public_path('/storage/images/'.$fileName));
            }
            $user->update([
                'photos' => $fileName
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            if($user->photos != 'incognito.png') {
                unlink('../public/storage/images/'. $user->photos);
            }
            $user->delete();
        }
    }
}
