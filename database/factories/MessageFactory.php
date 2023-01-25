<?php

namespace Database\Factories;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class MessageFactory extends Factory
{
    public function definition()
    {
        $channels = Channel::all()->load('members')->toArray();
        $channel = Arr::random($channels);
        $author = Arr::random($channel['members']);
        $content = fake()->sentences(rand(1, 3));

        if (is_array($content))
            $content = join(". ", $content);

        return [
            'channel_id' => $channel['id'],
            'author_id' => $author['id'],
            'content' => $content,
        ];
    }
}
