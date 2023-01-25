<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChannelController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $user->load('channels');

        return $user['channels']->toJson();
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'type' => 'required|in:dm,group',
            'members' => 'required',
        ]);

        array_push($data['members'], $user['id']);

        $data['members'] = array_unique($data['members']);

        if ($data['type'] === 'dm' && count($data['members']) !== 2) {
            return response('You pass too much or too little members for this channel', Response::HTTP_BAD_REQUEST);
        }

        $channel = new Channel($data);

        $channel
            ->members()
            ->attach($data['members']);

        return $channel->toJson();
    }

    public function show($id)
    {
        return Channel::findOrFail($id)->toJson();
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $channel = Channel::findOrFail($id);

        if (!$channel->members()->where('id', '=', $user['id'])->first()) {
            return response('You have not access', 403);
        }

        $channel->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
