<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class HomeController extends Controller
{
    public function home()
    {
        
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
        $event->startDateTime = Carbon::parse($request->date . ' ' . $request->start_time);
        $event->endDateTime = Carbon::parse($request->date . ' ' . $request->end_time);
        $event->save();

        return back()->with('success', 'Event created successfully');
    }

    public function storeEvent()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer 165f7a3a74e3ebcb1ddfbc0bad48608f4610feede279518b8d74fe423ab2c2ee',
            'accept' => 'text/plain',
            'Cookie' => '_session_id=64b88ac4fef20335ca0a2eefa0d2fab5; do_not_sell=; logged_in=true',
        ])->get('https://www.universe.com/api/v2/guestlists', [
            'limit' => 1000,
            'offset' => 520,
        ]);


        $data = $response->json();

        $formattedArray = [];

        foreach ($data['data']['guestlist'] as $item) {
            // Fetch and print event details
            $event_start_time = date('Y-m-d H:i:s', $item['event']['start_stamp']);
            $event_end_time = date('Y-m-d H:i:s', $item['event']['end_stamp']);
            $event_id = $item['event']['id'];

            // Fetch and print answers details
            $answers = [];
            foreach ($item['answers'] as $answer) {
                if ($answer['name'] === 'Email') {
                    $answers['email'] = $answer['value'];
                }
            }

            // Push data to formatted array
            $formattedArray[] = [
                'event_start_time' => $event_start_time,
                'event_end_time' => $event_end_time,
                'event_id' => $event_id,
                'ticket_name' => $item['ticket_type']['name'],
                'email' => $answers['email'],
            ];
        }

        foreach($formattedArray as $data){
            //create a new event
            $event = new Event;
            $event->name = $data['ticket_name'];
            $event->description = $data['email'];
            $event->startDateTime = \Carbon\Carbon::parse($data['event_start_time']);
            $event->endDateTime = \Carbon\Carbon::parse($data['event_end_time']);
            $event->save();
        }
       
        return back()->with('success', 'Event created successfully');
    }

    public function storeEventss()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer 165f7a3a74e3ebcb1ddfbc0bad48608f4610feede279518b8d74fe423ab2c2ee',
            'accept' => 'text/plain',
            'Cookie' => '_session_id=64b88ac4fef20335ca0a2eefa0d2fab5; do_not_sell=; logged_in=true',
        ])->get('https://www.universe.com/api/v2/guestlists', [
            'limit' => 1000,
            'offset' => 520,
        ]);

        $data = $response->json();

        $events = Event::get();
        // dd($events);

        $formattedArray = [];

        foreach ($data['data']['guestlist'] as $item) {
            // Fetch and print event details
            $event_start_time = date('Y-m-d H:i:s', $item['event']['start_stamp']);
            $event_end_time = date('Y-m-d H:i:s', $item['event']['end_stamp']);
            $event_id = $item['event']['id'];

            // Fetch and print answers details
            $answers = [];
            foreach ($item['answers'] as $answer) {
                if ($answer['name'] === 'Email') {
                    $answers['email'] = $answer['value'];
                }
            }

            // Push data to formatted array
            $formattedArray[] = [
                'event_start_time' => $event_start_time,
                'event_end_time' => $event_end_time,
                'event_id' => $event_id,
                'ticket_name' => $item['ticket_type']['name'],
                'email' => $answers['email'],
            ];
        }

        foreach($formattedArray as $data){

            $events = Event::get();

            $startDateTime = Carbon::parse($data['event_start_time']);
            $endDateTime = Carbon::parse($data['event_end_time']);
            
            // Retrieve all events
            $allEvents = Event::get();
            
            // Check if there's an existing event at the same time
            $existingEvent = $allEvents->first(function ($event) use ($startDateTime, $endDateTime) {
                return $event->startDateTime <= $endDateTime && $event->endDateTime >= $startDateTime;
            });

            if ($existingEvent) {
                // Update existing event
                $existingEvent->name = $data['ticket_name'];
                $existingEvent->description = $data['email'];
                // ... add more fields as needed
                $existingEvent->save();
            
                $event = $existingEvent; // Use the existing event
            } else {
                // Create a new event
                $event = Event::create([
                    'name' =>  $data['ticket_name'],
                    'startDateTime' => $startDateTime,
                    'endDateTime' => $endDateTime,
                    'description' => $data['email'],
                    // ... add more fields as needed
                ]);
            }

            CalendarEvent::updateOrCreate(
                [
                    'event_start_time' => $startDateTime,
                    'event_end_time' => $endDateTime,
                ],
                [
                    'google_event_id' => $event->id,
                    'name' =>  $data['ticket_name'],
                    'email' => $data['email'],
                ]
            );
            
        }
       
        return back()->with('success', 'Event created successfully');
    }
}
