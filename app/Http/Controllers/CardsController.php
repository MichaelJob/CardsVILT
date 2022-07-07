<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class CardsController extends Controller
{
    public function index()
    {
        return Inertia::render('Cards/Index', [
            'filters' => Request::all('search', 'trashed'),
            'cards' => Auth::user()->account->cards()
                ->orderBy('id')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(6)
                ->withQueryString()
                ->through(fn ($card) => [
                    'id' => $card->id,
                    'main_subject' => $card->main_subject,
                    'subject' => $card->subject,
                    'front' => $card->front,
                    'back' => $card->back,
                    'deleted_at' => $card->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Cards/Create');
    }

    public function store()
    {
        Auth::user()->account->Cards()->create(
            Request::validate([
                'main_subject' => ['required', 'max:255'],
                'subject' => ['nullable', 'max:255'],
                'front' => ['nullable', 'max:255'],
                'back' => ['nullable', 'max:255'],
            ])
        );

        return Inertia::render('Cards/Create');
    }

    public function edit(Card $card)
    {
        return Inertia::render('Cards/Edit', [
            'card' => [
                'id' => $card->id,
                'main_subject' => $card->main_subject,
                'subject' => $card->subject,
                'front' => $card->front,
                'back' => $card->back,
                'deleted_at' => $card->deleted_at,
            ],
        ]);
    }

    public function update(Card $card)
    {
        $card->update(
            Request::validate([
                'main_subject' => ['required', 'max:255'],
                'subject' => ['nullable', 'max:255'],
                'front' => ['nullable', 'max:255'],
                'back' => ['nullable', 'max:255'],
            ])
        );

        return Redirect::back()->with('success', 'Card updated.');
    }

    public function destroy(Card $card)
    {
        $card->delete();

        return Redirect::back()->with('success', 'Card deleted.');
    }

    public function restore(Card $card)
    {
        $card->restore();

        return Redirect::back()->with('success', 'Card restored.');
    }
}
