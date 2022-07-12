<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class CardsController extends Controller
{
    public function index()
    {
        $cards = Auth::user()->account->cards()
            ->orderBy('id', 'desc')
            ->filter(Request::only('search', 'trashed'))
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($card) => [
                'id' => $card->id,
                'main_subject' => $card->main_subject,
                'subject' => $card->subject,
                'front' => $card->front,
                'back' => $card->back,
                'deleted_at' => $card->deleted_at,
            ]);

        return Inertia::render('Cards/Index', [
            'filters' => Request::all('search', 'trashed'),
            'cards' => $cards,
            'cardscount' => Auth::user()->account->cards()->count(),   
            'listcount' => $cards->total(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Cards/Create');
    }

    public function store()
    {
        $success = Auth::user()->account->Cards()->create(
            Request::validate([
                'main_subject' => ['required', 'max:255'],
                'subject' => ['nullable', 'max:255'],
                'front' => ['nullable', 'max:255'],
                'back' => ['nullable', 'max:255'],
            ])
        );

        if ($success instanceof Card) {
            //create next card
            return Inertia::render('Cards/Create');
        } else {
            //return with error
            return Redirect::back();
        }
    }

    public function edit(Card $card)
    {

        $data = [
            'id' => $card->id,
            'main_subject' => $card->main_subject,
            'subject' => $card->subject,
            'front' => $card->front,
            'front_photo_path' => $card->front_photo_path,
            'front_photo' => $card->front_photo_path ? URL::route('image', ['path' => $card->front_photo_path]) : null,
            'back' => $card->back,
            'back_photo_path' => $card->back_photo_path,
            'back_photo' => $card->back_photo_path ? URL::route('image', ['path' => $card->back_photo_path]) : null,
            'deleted_at' => $card->deleted_at,
        ];

        return Inertia::render('Cards/Edit', [
            'card' => $data,
        ]);
    }

    public function update(Card $card)
    {
        $card->update(
            Request::validate([
                'main_subject' => ['nullable', 'max:255'],
                'subject' => ['nullable', 'max:255'],
                'front' => ['nullable', 'max:255'],
                'back' => ['nullable', 'max:255'],
            ])
        );

        if (Request::file('front_photo')) {
            $card->update(['front_photo_path' => Request::file('front_photo')->store(Auth::user()->account->id.'/cards')]);
        }
        if (Request::file('back_photo')) {
            $card->update(['back_photo_path' => Request::file('back_photo')->store(Auth::user()->account->id.'/cards')]);
        }

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

     /**
     * Delete card photo from storage and path in card model
     * 
     * @param Card $card 
     * 
     * @return Redirect
     */
    public function deleteimg(Card $card, String $front)
    {
            if ($front=='true') {
                $photo = $card->front_photo_path;
                if ($photo != null) {
                    Storage::delete($photo);
                    $card->front_photo_path = null;
                    $card->save();
                    return Redirect::back()->with('success', 'Front photo deleted');
                }
            } else {
                $photo = $card->back_photo_path;
                if ($photo != null) {
                    Storage::delete($photo);
                    $card->back_photo_path = null;
                    $card->save();
                    return Redirect::back()->with('success', 'Back photo deleted');
                }
            }
        return Redirect::back()->with('error', 'error on Deletion');
    }
}
