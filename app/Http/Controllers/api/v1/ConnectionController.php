<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\connections\acceptConnectionRequest;
use App\Http\Requests\api\v1\connections\sendConnectionRequest;
use App\Http\Resources\api\v1\connections\connectionResource;
use App\Models\Connection;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    //
    public function myConnections()
    {
        $user = auth()->user();

        $connections = Connection::where(function ($q) use ($user) {
            $q->where('requester_id', $user->id)
                ->orWhere('requested_id', $user->id);
        })
            ->where('status', 'accepted')
            ->latest()
            ->get();

        return $this->success(message: 'My connections retrieved successfully', data: connectionResource::collection($connections));
    }
    public function send(sendConnectionRequest $request)
    {
        $user = auth()->user();

        if ($user->id == $request->requested_id) {
            $this->forbidden(message: 'You cannot connect with yourself');
        }


        $existingConnection = Connection::where(function ($q) use ($user, $request) {
            $q->where('requester_id', $user->id)
                ->where('requested_id', $request->requested_id);
        })->orWhere(function ($q) use ($user, $request) {
            $q->where('requester_id', $request->requested_id)
                ->where('requested_id', $user->id);
        })->first();



        if ($existingConnection) {
            if ($existingConnection->status == 'pending') {
                return $this->error(message: 'Connection request already sent but pending');
            } elseif ($existingConnection->status == 'accepted') {
                return $this->error(message: 'You are already connected');
            } elseif ($existingConnection->status == 'rejected') {
                $existingConnection->update([
                    'status' => 'pending',
                    'requester_id' => $user->id,
                    'requested_id' => $request->requested_id,
                ]);
                return $this->success(message: 'Connection request sent again successfully');
            }
        }

         Connection::create([
            'requester_id' => $user->id,
            'requested_id' => $request->requested_id,
            'status' => 'pending',
        ]);

        return $this->success(message: 'Connection sent successfully');
    }
    public function accept(AcceptConnectionRequest $request)
    {
        $user = auth()->user();

        $connection = Connection::where('id', $request->connection_id)
            ->where('requested_id', $user->id)
            ->first();
        if (!$connection) {
            return $this->error(message: 'You cannot accept this connection request.');
        }

        if ($connection->status === 'accepted') {
            return $this->error(message: 'Connection already accepted.');
        }

        if ($connection->status !== 'pending') {
            return $this->error(message: 'Connection request is not pending.');
        }

        $connection->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        return $this->success(message: 'Connection accepted successfully.');
    }

    public function reject(acceptConnectionRequest $request)
    {

        $user = auth()->user();

        $connection = Connection::where('id', $request->connection_id)
            ->where('requested_id', $user->id)
            ->first();
        if (!$connection) {
            return $this->forbidden(message: 'You cannot accept this connection request. you are not the requested user.');
        }
        $connection->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return $this->success(message: 'Connection rejected successfully');
    }


}
