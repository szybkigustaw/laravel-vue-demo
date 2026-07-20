<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CardManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_when_opening_the_card_register(): void
    {
        $this->get(route('cards.index'))
            ->assertRedirect(route('login'));
    }

    public function test_users_only_see_their_own_cards_with_a_masked_pin(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $card = Card::factory()->for($user)->create([
            'card_number' => '12345678901234567890',
            'pin' => '1234',
        ]);
        Card::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->get(route('cards.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Cards/Index')
                ->has('cards.data', 1)
                ->where('cards.data.0.id', $card->id)
                ->where('cards.data.0.card_number', '12345678901234567890')
                ->where('cards.data.0.masked_pin', '****')
                ->missing('cards.data.0.pin')
            );
    }

    public function test_users_can_create_a_card_and_its_pin_is_encrypted_at_rest(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('cards.store'), $this->validCardData())
            ->assertRedirect(route('cards.index'));

        $card = Card::query()->sole();

        $this->assertSame($user->id, $card->user_id);
        $this->assertSame('1234', $card->pin);
        $this->assertNotSame('1234', $card->getRawOriginal('pin'));
    }

    public function test_card_fields_are_required(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('cards.store'), [])
            ->assertInvalid([
                'card_number',
                'pin',
                'activation_date',
                'expiration_date',
                'balance',
            ]);
    }

    public function test_card_fields_must_follow_the_expected_format_and_date_order(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('cards.store'), [
                'card_number' => 'not-a-card-number',
                'pin' => '12',
                'activation_date' => '2026-07-20T12:00',
                'expiration_date' => '2025-01-01',
                'balance' => '-1.001',
            ])
            ->assertInvalid([
                'card_number',
                'pin',
                'expiration_date',
                'balance',
            ]);
    }

    public function test_card_numbers_must_be_unique(): void
    {
        $user = User::factory()->create();
        Card::factory()->for($user)->create(['card_number' => '12345678901234567890']);

        $this->actingAs($user)
            ->post(route('cards.store'), $this->validCardData())
            ->assertInvalid('card_number');
    }

    public function test_users_can_update_and_delete_their_own_cards(): void
    {
        $user = User::factory()->create();
        $card = Card::factory()->for($user)->create();
        $data = [
            ...$this->validCardData(),
            'card_number' => '99999999999999999999',
            'pin' => '9876',
            'balance' => '45.50',
        ];

        $this->actingAs($user)
            ->put(route('cards.update', $card), $data)
            ->assertRedirect(route('cards.index'));

        $this->assertDatabaseHas('cards', [
            'id' => $card->id,
            'card_number' => '99999999999999999999',
            'balance' => '45.50',
        ]);

        $this->actingAs($user)
            ->delete(route('cards.destroy', $card))
            ->assertRedirect(route('cards.index'));

        $this->assertDatabaseMissing('cards', ['id' => $card->id]);
    }

    public function test_users_cannot_access_or_mutate_another_users_card(): void
    {
        $user = User::factory()->create();
        $card = Card::factory()->create();

        $this->actingAs($user)
            ->get(route('cards.edit', $card))
            ->assertForbidden();

        $this->actingAs($user)
            ->put(route('cards.update', $card), $this->validCardData())
            ->assertForbidden();

        $this->actingAs($user)
            ->delete(route('cards.destroy', $card))
            ->assertForbidden();
    }

    public function test_cards_are_paginated_for_the_owner(): void
    {
        $user = User::factory()->create();
        Card::factory()->count(16)->for($user)->create();

        $this->actingAs($user)
            ->get(route('cards.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Cards/Index')
                ->has('cards.data', 15)
                ->where('cards.current_page', 1)
                ->where('cards.last_page', 2)
                ->where('cards.total', 16)
            );
    }

    public function test_cards_are_deleted_when_their_owner_is_deleted(): void
    {
        $user = User::factory()->create();
        $card = Card::factory()->for($user)->create();

        $user->delete();

        $this->assertDatabaseMissing('cards', ['id' => $card->id]);
    }

    /**
     * @return array<string, string>
     */
    private function validCardData(): array
    {
        return [
            'card_number' => '12345678901234567890',
            'pin' => '1234',
            'activation_date' => '2026-07-20T12:00',
            'expiration_date' => '2027-07-20',
            'balance' => '125.00',
        ];
    }
}
