<?php

namespace App\Http\Controllers\API;

use App\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchedulesController extends Controller
{
    /**
     * Display a listing DB records table schedules and reverse them.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Schedule::all()->sortByDesc('id');

    }

    /**
     * Store a newly created validated record in DB schedules table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Gate::allows('admin')) {
            request()->validate([
                'title' => ['required', 'min:3'],
            ]);

            $object = new Schedule();

            $object->title = request('title');
            $object->comment = request('comment');
            $object->mon = request('mon');
            $object->tue = request('tue');
            $object->wen = request('wen');
            $object->thr = request('thr');
            $object->fri = request('fri');
            $object->sat = request('sat');
            $object->sun = request('sun');

            $object->save();
        }
    }

    /**
     * Update the specified DB record in schedules table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        if (\Gate::allows('admin')) {
            $object = Schedule::all()->where('id','=',$schedule->id)->first();

            request()->validate([
                'title' => ['required', 'min:3'],
            ]);

            $object->update([
                'title' => $request['title'],
                'comment' => request('comment'),
                'mon' => request('mon'),
                'tue' => request('tue'),
                'wen' => request('wen'),
                'thr' => request('thr'),
                'fri' => request('fri'),
                'sat' => request('sat'),
                'sun' => request('sun'),
            ]);
        }
    }

    /**
     * Remove the specified record from DB coachs table.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        if (\Gate::allows('admin')) {

            $schedule->delete();
        }
    }
}
