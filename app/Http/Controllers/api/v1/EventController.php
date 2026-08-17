<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\events\createEventsRequest;
use App\Http\Requests\api\v1\events\getEventsRequest;
use App\Http\Requests\api\v1\events\updateEventsRequest;
use App\Http\Requests\api\v1\events\updateStatusRequest;
use App\Http\Resources\api\v1\events\EventResource;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    //
    public function allEvents(getEventsRequest $request)
    {
        $limit  = $request->limit ?? 10;
        $status = $request->status ?? 'all';
        $isMine = $request->isMine ?? false;


        if($isMine){
            $query = Event::where('user_id', auth()->id());
        }else{
            $query = Event::query();
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }
        $events = $query->latest()->paginate($limit);
        $paginationInfo = getPaginationInfo($events, $limit);
        return $this->success(message: 'successfully', data: [
            'events' => eventResource::collection($events),
            'pagination' => $paginationInfo
        ]);
    }
    public function store(createEventsRequest $request)
    {

       $event = new Event();
       $event->user_id = Auth::id();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->event_date_time = $request->event_date_time;
        $event->location = $request->location;
        $event->venue = $request->venue;
        $event->event_type = $request->event_type;
        $event->ticket_price = $request->ticket_price;
        $event->max_attendees = $request->max_attendees;
        $event->status = 'pending';

        // Handle image upload
        if ($request->hasFile('event_image')) {
            $event->event_image = ImageService::addImage('images/events', $request->file('event_image'), 'event_');
        }
        $event->save();


        return $this->success(message: 'Event created successfully');
    }
    //show
    public function show($eventId)
    {
        $event = Event::find($eventId);
        if (!$event) {
            return $this->notFound(message: 'Event not found');
        }
        return $this->success(message: 'successfully', data: [
            'event' => new EventResource($event)
        ]);
    }

    public function update(updateEventsRequest $request, $eventId)
    {

        $event = Event::query()->where('user_id',auth()->user()->id)->find($eventId);
        if (!$event) {
            return $this->notFound(message: 'Event not found');
        }


        $event->title = $request->title ?? $event->title;
        $event->description = $request->description ?? $event->description;
        $event->event_date_time = $request->event_date_time ?? $event->event_date_time;
        $event->location = $request->location ?? $event->location;
        $event->venue = $request->venue ?? $event->venue;
        $event->event_type = $request->event_type ?? $event->event_type;
        $event->ticket_price = $request->ticket_price ?? $event->ticket_price;
        $event->status = $request->status ?? $event->status;
        $event->max_attendees = $request->max_attendees ?? $event->max_attendees;

        // Handle image upload
        if ($request->hasFile('event_image')) {
            $event->event_image = ImageService::updateImage('images/events/',$request->event_image, $event->event_image, 'event_');
        }
        $event->save();


        return $this->success(message: 'Event updated successfully');
    }
    //destroy
    public function destroy($eventId)
    {
        $event = Event::query()->where('user_id',auth()->user()->id)->find($eventId);
        if (!$event) {
            return $this->notFound(message: 'Event not found');
        }

        // Delete event image if exists
        if ($event->event_image) {
            ImageService::deleteImage('images/events/',$event->event_image);
        }

        $event->delete();

        return $this->success(message: 'Event deleted successfully');
    }
    //join
    public function join($eventId)
    {
        $event = Event::find($eventId);
        //not found
        if (!$event) {
            return $this->notFound(message: 'Event not found');
        }

        //max attendees check
        $joinedCount = $event->attendees()->where('status', 'going')->count();
        if ($joinedCount >= (int) $event->max_attendees) {
            $maxAttendees = (int) $event->max_attendees;
            return $this->forbidden(message: "Event is full. $joinedCount out of $maxAttendees attendees have joined.");
        }

        if ($event->status === 'cancelled') {
            return $this->forbidden(message: 'Event cannot be cancelled');
        }
        EventAttendee::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id' => Auth::id(),
            ],
            [
                'status' => 'going',
            ]
        );

        return $this->success(message: 'Event joined successfully');
    }
    //cancelJoin
    public function cancelJoin($eventId)
    {
        $event = Event::find($eventId);
        //not found
        if (!$event) {
            return $this->notFound(message: 'Event not found');
        }

        EventAttendee::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id' => Auth::id(),
            ],
            [
                'status' => 'cancelled',
            ]
        );

        return $this->success(message: 'Event join cancelled successfully');
    }

    public function updateStatus(updateStatusRequest $request, $eventId)
    {
        $event = Event::find($eventId);
        //not found
        if (!$event) {
            return $this->notFound(message: 'Event not found');
        }

        $event->status = $request->status ?? $event->status;
        $event->save();

        return $this->success(message: 'status updated successfully');
    }

}
