<?php

namespace App\Http\Controllers;

use App\Queue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QueueAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $queue = Queue::all()->toArray();

        return response()->json([
            'queue' => $queue
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $entry = new Queue;

        $entry->title = (is_null($request->input('customer.title')) ? null : $request->input('customer.title'));
        $entry->first_name = (is_null($request->input('customer.first_name')) ? null : $request->input('customer.first_name'));
        $entry->last_name = (is_null($request->input('customer.last_name')) ? null : $request->input('customer.last_name'));
        $entry->organisation = (is_null($request->input('customer.organisation')) ? null : $request->input('customer.organisation'));
        $entry->service = $request->input('customer.service');
        $entry->type = $request->input('customer.type');
        $entry->queued_at = Carbon::now()->toDateTimeString();

        $entry->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {
        $entry = Queue::findOrFail($request->input('customer.id'));

        $entry->title = (is_null($request->input('customer.title')) ? null : $request->input('customer.title'));
        $entry->first_name = (is_null($request->input('customer.first_name')) ? null : $request->input('customer.first_name'));
        $entry->last_name = (is_null($request->input('customer.last_name')) ? null : $request->input('customer.last_name'));
        $entry->organisation = (is_null($request->input('customer.organisation')) ? null : $request->input('customer.organisation'));
        $entry->service = $request->input('customer.service');
        $entry->type = $request->input('customer.type');
        $entry->queued_at = Carbon::now()->toDateTimeString();

        $entry->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
