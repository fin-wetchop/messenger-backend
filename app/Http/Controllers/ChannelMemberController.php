<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelMemberController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $channelId = $request->route('channel_id');
        $channel = Channel::findOrFail($channelId);

        if (!$channel->members()->where('users.id', '=', $user['id'])->first()) {
            return response('You have not access', 403);
        }

        $channel->load('members');

        return response()->json($channel['members']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required',
        ]);

        $user = $request->user();
        $channelId = $request->route('channel_id');
        $channel = Channel::findOrFail($channelId);

        if (!$channel->members()->where('users.id', '=', $user['id'])->first()) {
            return response('You have not access', 403);
        }

        $channel->load('members');

        $channel
            ->members()
            ->sync(
                array_unique(
                    array_merge(
                        $data['ids'],
                        array_map(
                            function ($channel) {
                                return $channel['id'];
                            },
                            $channel['members']
                        )
                    )
                )
            );

        return response()->json($channel['members']);
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required',
        ]);

        $user = $request->user();
        $channelId = $request->route('channel_id');
        $channel = Channel::findOrFail($channelId);

        if (!$channel->members()->where('users.id', '=', $user['id'])->first()) {
            return response('You have not access', 403);
        }

        $channel
            ->members()
            ->detach(array_unique($data['ids']));

        $channel->load('members');

        return response()->json($channel['members']);
    }
}
