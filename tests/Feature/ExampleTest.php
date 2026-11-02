<?php

use App\Models\Idea;
use App\Models\User;

test('It Belongs To a User', function () {
    $idea = Idea::factory()->create();
    expect($idea->user)->toBeInstanceOf(User::class);
});

test('it hasMany to steps', function () {
    $idea = Idea::factory()->make();
    expect($idea->steps)->toBeEmpty();
});

it('it hasMany ideas', function () {
    $user = User::factory()->create();
    $user->ideas()->create([
        'description' => 'anything you want to write here!',
        'title' => 'My First Idea',
    ]);
});
