<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Http\Resources\CardCollection;
use App\Http\Resources\CardResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CardsApiController extends Controller
{
    public function index()
    {
        return new CardCollection(Card::all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = Card::find($id);
        if (is_null($card)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json(new CardResource($card));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'main_subject' => ['nullable', 'max:255'],
                'subject' => ['nullable', 'max:255'],
                'front' => ['nullable', 'max:255'],
                'back' => ['nullable', 'max:255'],
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors());       
        }
      
        $card = Auth::user()->account->contacts()->create(
            [
                'main_subject' => $request->main_subject,
                'subject' => $request->subject,
                'front' => $request->front,
                'back' => $request->back,
             ]
        );
      

        if ($card->save()) {
            return response()->json(
                ['Card created successfully.', new CardResource($card)]
            );
        } else {
            return response()->json(
                ['Card store failed.']
            );
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT request to 127.0.0.1:8000/api/v1/apicards/{id}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {

        if ($id != $request->id) {
            return response()->json(
                ['Card update failed. Parameter id is not equal to body id.']
            );
        }

        $validator = Validator::make(
            $request->all(),
            [
                'main_subject' => $request->main_subject,
                'subject' => $request->subject,
                'front' => $request->front,
                'back' => $request->back,
            ]
        );
            
        if ($validator->fails()) {
            return response()->json($validator->errors());       
        }
    
        $card = Card::find($id);
        if ($card) {
            $card->main_subject = $request->main_subject;
            $card->subject      = $request->subject;
            $card->front        = $request->front;
            $card->back         = $request->back;

            if ($card->save()) {
                return response()->json(
                    ['Card updated successfully.',  new CardResource($card)]
                );
            } else {
                return response()->json(
                    ['Card update failed.']
                );
            }

        } else {
            return response()->json(
                ['Card not found.']
            );
        }
    }



}
