<?php

use App\Models\User;
use App\Models\Guest;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Notification::fake();
});

it('shows guest menu page for authenticated guest', function () {
    $guest = Guest::factory()->create();
    $menu = Menu::factory()->create();

    $this->actingAs($guest, 'guest')
        ->get(route('guest.menu'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) =>
            $page->component('Guests/Menu')
                ->has('menus', 1)
                ->where('guest.id', $guest->id)
        );
});

it('creates an order for a guest and sends notification', function () {
    $guest = Guest::factory()->create();
    $menu = Menu::factory()->create(['price' => 100]);

    $this->actingAs($guest, 'guest')
        ->post(route('orders.store'), [
            'orders' => [
                ['menu_id' => $menu->id, 'quantity' => 2],
            ],
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $order = Order::first();
    expect($order->total)->toBe(200);
    expect($order->guest_id)->toBe($guest->id);

    Notification::assertSentTo(
        [$guest],
        OrderStatusUpdated::class,
        fn ($notification, $channels) => $notification->newStatus === 'pending'
    );
});

it('creates an order for a user with discount applied', function () {
    $user = User::factory()->create();
    $menu = Menu::factory()->create(['price' => 100]);

    // Attach a discount
    $user->discount()->create(['percentage' => 10]);

    $this->actingAs($user)
        ->post(route('orders.store'), [
            'orders' => [
                ['menu_id' => $menu->id, 'quantity' => 2],
            ],
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $order = Order::first();
    expect($order->total)->toBe(180); // 200 - 10%
    expect($order->user_id)->toBe($user->id);

    Notification::assertSentTo(
        [$user],
        OrderStatusUpdated::class,
        fn ($notification) => $notification->newStatus === 'pending'
    );
});

it('adds an item to existing order', function () {
    $order = Order::factory()->create();
    $menu = Menu::factory()->create();

    $this->post(route('orders.addItem', $order), [
        'menu_id' => $menu->id,
        'quantity' => 3,
    ])->assertRedirect()
      ->assertSessionHas('success');

    expect($order->orderItems()->count())->toBe(1);
});

it('removes an item from existing order', function () {
    $orderItem = OrderItem::factory()->create();
    $order = $orderItem->order;

    $this->delete(route('orders.removeItem', [$order, $orderItem]))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($order->orderItems()->count())->toBe(0);
});

it('updates order status and sends notification', function () {
    $order = Order::factory()->create(['status' => 'pending']);
    $user = User::factory()->create();
    $order->user()->associate($user)->save();

    $this->put(route('orders.updateStatus', $order))
        ->assertRedirect()
        ->assertSessionHas('success');

    $order->refresh();
    expect($order->status)->toBe('approved');

    Notification::assertSentTo(
        [$user],
        OrderStatusUpdated::class,
        fn ($notification) => $notification->newStatus === 'approved'
    );
});
