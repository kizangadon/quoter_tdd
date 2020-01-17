<?php

namespace App\Http\Controllers;

use App\Quote;

use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function store(Request $request){
        Quote::create($this->validateQuoteRequest($request));

        $route = "/quotes";

        return redirect($route);
    }

    public function update(Request $request, Quote $quote){
        $quote->update($this->validateQuoteRequest($request));

        $route = "/quote/" . $quote->id;

        return redirect($route);
    }

    private function validateQuoteRequest(Request $request){
        return $request->validate([
            'quote' => 'required',
            'author' => 'required'
        ]);
    }
}
