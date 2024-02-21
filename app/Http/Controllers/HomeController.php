<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function home()
    {
        // $events = Event::get();

        // Event::create([
        //     'name' => 'A new event',
        //     'startDateTime' => \Carbon\Carbon::now(),
        //     'endDateTime' => \Carbon\Carbon::now()->addHour(),
        // ]);
        // dd($events);

        return view('google-calendar');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        //create a new event
        $event = new Event;

        $event->name = $request->description;
        // $event->description = 'Event description';
        $event->startDateTime = Carbon::parse($request->date.' '.$request->start_time);
        $event->endDateTime = Carbon::parse($request->date.' '.$request->end_time);
        $event->save();

        return back()->with('success', 'Event created successfully');
        // dd($request->all());
    }
}
