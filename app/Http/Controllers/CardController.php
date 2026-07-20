<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CardController extends Controller
{
    /**
     * Display the authenticated user's cards.
     */
    public function index(Request $request): Response
    {
        $cards = $request->user()
            ->cards()
            ->latest('activation_date')
            ->paginate(15)
            ->through(fn (Card $card): array => [
                'id' => $card->id,
                'card_number' => $card->card_number,
                'masked_pin' => '****',
                'activation_date' => $card->activation_date->toIso8601String(),
                'expiration_date' => $card->expiration_date->toDateString(),
                'balance' => $card->balance,
            ]);

        return Inertia::render('Cards/Index', [
            'cards' => $cards,
        ]);
    }

    /**
     * Show the form for creating a card.
     */
    public function create(): Response
    {
        return Inertia::render('Cards/Create');
    }

    /**
     * Store a newly created card.
     */
    public function store(StoreCardRequest $request): RedirectResponse
    {
        $request->user()->cards()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Card created.']);

        return to_route('cards.index');
    }

    /**
     * Show the form for editing a card.
     */
    public function edit(Request $request, Card $card): Response
    {
        $this->authorize('view', $card);

        return Inertia::render('Cards/Edit', [
            'card' => [
                'id' => $card->id,
                'card_number' => $card->card_number,
                'pin' => $card->pin,
                'activation_date' => $card->activation_date->format('Y-m-d\\TH:i'),
                'expiration_date' => $card->expiration_date->toDateString(),
                'balance' => $card->balance,
            ],
        ]);
    }

    /**
     * Update the specified card.
     */
    public function update(UpdateCardRequest $request, Card $card): RedirectResponse
    {
        $card->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Card updated.']);

        return to_route('cards.index');
    }

    /**
     * Remove the specified card.
     */
    public function destroy(Request $request, Card $card): RedirectResponse
    {
        $this->authorize('delete', $card);

        $card->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Card deleted.']);

        return to_route('cards.index', $request->query());
    }
}
