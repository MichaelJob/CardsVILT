<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\Card;

class LearnController extends Controller
{
    public function index()
    {
        return Inertia::render('Learn/Index', [
            'filters' => Request::all('search', 'trashed'),
            'cards' => Card::where('deleted_at', null)
                ->orderBy('id')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(1)
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

    public function indexguest()
    {
        return Inertia::render('Learn/Indexguest', [
            'filters' => Request::all('search', 'trashed'),
            'cards' => Card::where('deleted_at', null)
                ->orderBy('id')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(1)
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
}
