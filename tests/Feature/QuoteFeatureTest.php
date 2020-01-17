<?php

namespace Tests\Feature;

use App\Quote;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuoteFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aQuote_canBeCreated_whenTheCorrectDetailsArePassed(){
        //Arrange
        $response = $this->post('/quote', [
            'quote' => 'My first real quote ever',
            'author' => 'Don Smoke'
        ]);
        $expectedQuoteCount = 1;
        //Act
        $quotes = Quote::all();
        //Assert
        $this->assertCount($expectedQuoteCount, $quotes);
    }

    /**
     * @test
     */
    public function aQuote_createsAnError_whenTheQuoteIsNotPassed(){
        //Arrange
        $response = $this->post('/quote', [
            'quote' => '',
            'author' => 'Don Smoke'
        ]);
        //Assert
        $response->assertSessionHasErrors('quote');
    }

    /**
     * @test
     */
    public function aQuote_createsAnError_whenTheAuthorIsNotPassed(){
        //Arrange
        $response = $this->post('/quote', [
            'quote' => 'Living my best life',
            'author' => ''
        ]);
        //Assert
        $response->assertSessionHasErrors('author');
    }

    /**
     * @test
     */
    public function aQuote_redirectsToTheExpectedPath_onceItHasBeenCreated(){
        //Arrange
        $quote = "My favourite quote";
        $author = "Don Smoke";
        $expectedRedirectPath = "/quotes";
        //Act
        $response = $this->post('/quote', $this->createQuoteData($quote, $author));
        //Assert
        $response->assertRedirect($expectedRedirectPath);
    }

    /**
     * @test
     */
    public function aQuote_canBeUpdated_whenTheCorrectDetailsArePassed(){
        //Arrange
        $expectedQuote = "Newly updated quote";
        //Act
        $this->post('/quote', [
            'quote' => 'Original quote',
            'author' => 'Don Smoke'
        ]);

        $route = '/quote/' . Quote::first()->id;

        $response = $this->put($route, [
            'quote' => 'Newly updated quote',
            'author' => 'Don Smoke'
        ]);

        $quote = Quote::first();
        //Assert
        $this->assertEquals($expectedQuote, $quote->quote);
    }

    /**
     * @test
     */
    public function aQuote_createsAnError_whenUpdatingAnTheQuoteIsNotPassed(){
        //Arrange
        $expectedQuote = "Newly updated quote";
        //Act
        $this->post('/quote', [
            'quote' => 'Original quote',
            'author' => 'Don Smoke'
        ]);

        $route = '/quote/' . Quote::first()->id;

        $response = $this->put($route, [
            'quote' => '',
            'author' => 'Don Smoke'
        ]);

        $quote = Quote::first();
        //Assert
        $response->assertSessionHasErrors('quote');
    }

    /**
     * @test
     */
    public function aQuote_createsAnError_whenUpdatingAnTheAuthorIsNotPassed(){
        //Arrange
        $expectedQuote = "Newly updated quote";
        //Act
        $this->post('/quote', [
            'quote' => 'Original quote',
            'author' => 'Don Smoke'
        ]);

        $route = '/quote/' . Quote::first()->id;

        $response = $this->put($route, [
            'quote' => 'Newly updated quote',
            'author' => ''
        ]);

        $quote = Quote::first();
        //Assert
        $response->assertSessionHasErrors('author');
    }

    /**
     * @test
     */
    public function aQuote_redirectsToTheExpectedPath_afterAQuoteHasBeenUpdated(){
        $this->withoutExceptionHandling();
        //Arrange
        $expectedPath = "Newly updated quote";
        //Act
        $this->post('/quote', [
            'quote' => 'Original quote',
            'author' => 'Don Smoke'
        ]);

        $route = '/quote/' . Quote::first()->id;

        $response = $this->put($route, [
            'quote' => 'Newly updated quote',
            'author' => 'Don Smoke'
        ]);
        //Assert
        $response->assertRedirect($route);
    }

    private function createQuoteData($quote, $author){
        return [
            'quote' => $quote,
            'author' => $author
        ];
    }
}
