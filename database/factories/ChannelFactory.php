<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ChannelFactory extends Factory
{
    public function definition()
    {
        $types = ['dm', 'group'];

        return [
            'name' => join(" ", fake()->words(2), ),
            'type' => $types[rand(0, count($types) - 1)],
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Channel $channel) {
            $userIds = array_map(function ($user) {
                return $user['id'];
            }
                , User::all(['id'])->toArray());

            if ($channel['type'] === 'dm')
                $channel->members()->attach(Arr::random($userIds, 2));
            else
                $channel->members()->attach(Arr::random($userIds, rand(1, min(5, count($userIds)))));
        });
    }
}
