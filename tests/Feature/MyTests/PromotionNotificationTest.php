<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Guest;
use App\Models\Promotion;
use App\Notifications\PromotionCreated;

class PromotionNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_receive_promotion_notification()
    {
        Notification::fake();

        $users = User::factory()->count(3)->create();
        $promotion = Promotion::factory()->create([
            'name' => 'Super Sale',
            'description' => '50% off all rooms!',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
        ]);

        // Send notification
        foreach ($users as $user) {
            $user->notify(new PromotionCreated($promotion));
        }

        // Assert notifications were sent
        Notification::assertSentTo($users, PromotionCreated::class, function ($notification, $channels, $notifiable) use ($promotion) {
            return $notification->promotion->id === $promotion->id;
        });
    }

    public function test_guests_receive_promotion_notification()
    {
        Notification::fake();

        $guests = Guest::factory()->count(3)->create();
        $promotion = Promotion::factory()->create([
            'name' => 'Weekend Special',
            'description' => 'Book 2 nights, get 1 free!',
            'start_date' => now(),
            'end_date' => now()->addDays(3),
        ]);

        foreach ($guests as $guest) {
            $guest->notify(new PromotionCreated($promotion));
        }

        Notification::assertSentTo($guests, PromotionCreated::class, function ($notification, $channels, $notifiable) use ($promotion) {
            return $notification->promotion->id === $promotion->id;
        });
    }
}
