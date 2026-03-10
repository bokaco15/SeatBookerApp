<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HallStoreRequest;
use App\Http\Requests\HallUpdateRequest;
use App\Models\Hall;
use App\Models\SeatCategory;
use App\Models\Seat;
use App\Services\SeatService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HallController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $halls = Hall::all();
        return view('admin.halls.index', compact('halls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view('admin.halls.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HallStoreRequest $request) : RedirectResponse
    {
        $data = $request->validated();

        $hall = Hall::create($data);
        $vip = SeatCategory::where('name', 'VIP')->first();
        $normal = SeatCategory::where('name', 'Normal')->first();

        $seatService = new SeatService();
        $seatService->createSeats($hall, $vip, $normal);

        return redirect()->route('admin.halls.create')
            ->with('success', 'The hall is created and the seats are automatically generated.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hall $hall)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hall $hall) : View
    {
        return view('admin.halls.edit', compact('hall'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HallUpdateRequest $request, Hall $hall) : RedirectResponse
    {
        $validated = $request->validated();

        $hall->update($validated);

        return redirect()
            ->route('admin.halls.index')
            ->with('success', 'Hall updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hall $hall) : RedirectResponse
    {
        $hall->delete();
        return redirect()->route('admin.halls.index')->with('success', 'You have successfully deleted a hall.');
    }
}
