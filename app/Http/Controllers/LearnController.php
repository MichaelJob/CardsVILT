<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\Card;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

class LearnController extends Controller
{
    public function index()
    {
        return Inertia::render('Learn/Index', [
            'filters' => Request::all('search', 'topic'),
            'cards' => Card::where('deleted_at', null)
                ->inRandomOrder()
                ->filter(Request::only('search', 'topic'))
                ->paginate(1)
                ->withQueryString()
                ->through(fn ($card) => [
                    'id' => $card->id,
                    'main_subject' => $card->main_subject,
                    'subject' => $card->subject,
                    'front' => $card->front,
                    'back' => $card->back,
                    'front_photo' => $card->front_photo_path ? URL::route('image', ['path' => $card->front_photo_path, 'h' => 240]) : null,
                    'back_photo' => $card->back_photo_path ? URL::route('image', ['path' => $card->back_photo_path, 'h' => 240]) : null,
                    'deleted_at' => $card->deleted_at,
                ]),
                'topics' => DB::table('cards')
                    ->select('subject', DB::raw('count(*) as total'))
                    ->groupBy('subject')
                    ->get(),
        ]);
    }

    public function indexguest()
    {
        return Inertia::render('Learn/Indexguest', [
            'filters' => Request::all('search', 'topic'),
            'cards' => Card::where('deleted_at', null)
                ->inRandomOrder()
                ->learn(Request::only('search', 'topic'))
                ->paginate(1)
                ->withQueryString()
                ->through(fn ($card) => [
                    'id' => $card->id,
                    'main_subject' => $card->main_subject,
                    'subject' => $card->subject,
                    'front' => $card->front,
                    'back' => $card->back,
                    'front_photo' => $card->front_photo_path ? URL::route('image', ['path' => $card->front_photo_path, 'h' => 240]) : null,
                    'back_photo' => $card->back_photo_path ? URL::route('image', ['path' => $card->back_photo_path, 'h' => 240]) : null,
                    'deleted_at' => $card->deleted_at,
                ]),
            'topics' => DB::table('cards')
                ->select('subject', DB::raw('count(*) as total'))
                ->groupBy('subject')
                ->get(),
        ]);
    }
}
