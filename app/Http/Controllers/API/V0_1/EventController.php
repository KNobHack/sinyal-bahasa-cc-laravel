<?php

namespace App\Http\Controllers\API\V0_1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V0_1\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Event::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->get('paginate') === '1') {
            $event = Event::simplePaginate();
        } else {
            $event = Event::all();
        }

        return EventResource::collection($event->load('host', 'participants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'thumbnail' => ['required', 'image'], // jpg, jpeg, png, bmp, gif, svg, or webp
            'description' => ['required'],
            'date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'numeric', 'min:1', 'max:86400', 'lt:end_time'],
            'end_time' => ['required', 'numeric', 'min:1', 'max:86400', 'gt:start_time'],
            'lat' => ['required', 'decimal:6'],
            'lon' => ['required', 'decimal:6'],
            'max_participant' => ['required', 'integer'],

            'host_id' => ['integer', Rule::excludeIf(auth('api')->user()?->isNotAdministrator())],
        ]);

        $file = $request->file('thumbnail')->store('event-thumbnail');
        $url = Storage::url($file);

        $event = new Event();
        $event->name            = $validated['name'];
        $event->host_id         = $validated['host_id'] ?? auth('api')->id();
        $event->thumbnail_url   = $url;
        $event->thumbnail_path  = $file;
        $event->description     = $validated['description'];
        $event->date            = $validated['date'];
        $event->start_time      = $validated['start_time'];
        $event->end_time        = $validated['end_time'];
        $event->lat             = $validated['lat'];
        $event->lon             = $validated['lon'];
        $event->max_participant = $validated['max_participant'];
        $event->save();

        return (new EventResource($event->load('host', 'participants')))
            ->additional([
                'message' => 'Event berhasil di buat'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return new EventResource($event->load(['host', 'participants']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'thumbnail' => ['image'], // jpg, jpeg, png, bmp, gif, svg, or webp
            'description' => ['required'],
            'date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'numeric', 'min:1', 'max:86400', 'lt:end_time'],
            'end_time' => ['required', 'numeric', 'min:1', 'max:86400', 'gt:start_time'],
            'lat' => ['required', 'decimal:6'],
            'lon' => ['required', 'decimal:6'],
            'max_participant' => ['required', 'integer'],

            'host_id' => ['integer', Rule::excludeIf(auth('api')->user()?->isNotAdministrator())],
        ]);

        if ($request->hasFile('photo')) {
            Storage::delete($event->thumbnail_path);

            $file = $request->file('thumbnail')->store('event-thumbnail');
            $url = Storage::url($file);

            $event->thumbnail_url   = $url;
            $event->thumbnail_path  = $file;
        }

        $event->name            = $validated['name'];
        $event->host_id         = $validated['host_id'] ?? auth('api')->id();
        $event->description     = $validated['description'];
        $event->date            = $validated['date'];
        $event->start_time      = $validated['start_time'];
        $event->end_time        = $validated['end_time'];
        $event->lat             = $validated['lat'];
        $event->lon             = $validated['lon'];
        $event->max_participant = $validated['max_participant'];
        $event->save();

        return (new EventResource($event->load('host', 'participants')))
            ->additional([
                'message' => 'Event berhasil di buat'
            ]);
    }

    public function join(Request $request, Event $event)
    {
        $validated = $request->validate([
            'user_id' => ['integer', Rule::excludeIf(auth('api')->user()?->isNotAdministrator())],
        ]);

        $event->join($validated['user_id'] ?? null);

        return (new EventResource($event->load(['host', 'participants'])))
            ->additional([
                'message' => 'Berhasil join'
            ]);
    }

    public function disjoin(Request $request, Event $event)
    {
        $validated = $request->validate([
            'user_id' => ['integer', Rule::excludeIf(auth('api')->user()?->isNotAdministrator())],
        ]);

        $event->disjoin($validated['user_id'] ?? null);

        return (new EventResource($event->load(['host', 'participants'])))
            ->additional([
                'message' => 'Berhasil disjoin'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        Storage::delete($event->thumbnail_path);

        $event->delete();

        return response()->json([
            'message' => 'Event berhasil di hapus'
        ]);
    }
}
