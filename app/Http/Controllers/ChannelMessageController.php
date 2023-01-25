<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Utils\QS;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChannelMessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $channelId = $request->route('channel_id');
        $channel = Channel::findOrFail($channelId);

        if (!$channel->members()->where('users.id', '=', $user['id'])->first()) {
            return response('You have not access', 403);
        }

        $query = $channel->messages();

        $pagination = QS::pagination($request->query());

        return response()->json(
            $query
                ->orderBy('created_at', 'desc')
                ->paginate($pagination['amount'], '*', 'page', $pagination['page'])
        );
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $channelId = $request->route('channel_id');
        $channel = Channel::findOrFail($channelId);

        if (!$channel->members()->where('users.id', '=', $user['id'])->first()) {
            return response('You have not access', 403);
        }

        $data = $request->validate([
            'content' => 'required|min:3|max:2048',
        ]);

        return $channel->messages()->create([
            'author_id' => $user['id'],
            'content' => $data['content'],
        ])->toJson();
    }

    public function show(Request $request)
    {
        $user = $request->user();
        $channelId = $request->route('channel_id');
        $channel = Channel::findOrFail($channelId);

        if (!$channel->members()->where('users.id', '=', $user['id'])->first()) {
            return response('You have not access', 403);
        }

        return $channel
            ->messages()
            ->where('id', '=', $request->route('id'))
            ->firstOrFail();
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $channelId = $request->route('channel_id');
        $channel = Channel::findOrFail($channelId);

        if (!$channel->members()->where('users.id', '=', $user['id'])->first()) {
            return response('You have not access', 403);
        }

        $message = $channel
            ->messages()
            ->where('id', '=', $request->route('id'))
            ->firstOrFail();

        if ($message['author_id'] !== $user['id']) {
            return response('You have not access', 403);
        }

        $data = $request->validate([
            'content' => 'required|min:3|max:2048',
        ]);

        $message
            ->fill($data)
            ->save();

        return $message->toJson();
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $channelId = $request->route('channel_id');
        $channel = Channel::findOrFail($channelId);

        if (!$channel->members()->where('users.id', '=', $user['id'])->first()) {
            return response('You have not access', 403);
        }

        $message = $channel
            ->messages()
            ->where('id', '=', $request->route('id'))
            ->firstOrFail();

        if ($message['author_id'] !== $user['id']) {
            return response('You have not access', 403);
        }

        $message->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
