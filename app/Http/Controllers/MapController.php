<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kolirt\Openstreetmap\Facade\Openstreetmap;

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->input('q', ''); 
        $limit = 10;
        $maps = Openstreetmap::search($q, $limit);
        // dd($maps);p
        return view('maps', compact('maps'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function detail()
    {
        $det = "57592194";
        $detail = Openstreetmap::details($det);
        dd($detail);
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
