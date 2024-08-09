<?php

namespace Maxdev\Tickets\Http\Controllers\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Maxdev\Tickets\Http\Requests\TicketSupport\TicketSupportUpdateRequest;
use Maxdev\Tickets\Http\Resources\Admin\TicketSupportResource;
use Maxdev\Tickets\Http\Responses\SuccessResponse;
use Maxdev\Tickets\Models\TicketSupport;
use Illuminate\Routing\Controller;

class TicketSupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        return TicketSupportResource::collection(
            TicketSupport::latest('id')
                         ->paginate(config('admin.per_page'))
        );
    }


    /**
     * Display the specified resource.
     */
    public function show(TicketSupport $ticketSupport): JsonResource
    {
        return TicketSupportResource::make($ticketSupport);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TicketSupportUpdateRequest $request, TicketSupport $ticketSupport): JsonResource
    {
        $ticketSupport->update($request->validated());

        return TicketSupportResource::make($ticketSupport);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketSupport $ticketSupport)
    {
        $ticketSupport->delete();

        return new SuccessResponse();
    }
}
