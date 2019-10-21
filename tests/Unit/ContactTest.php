<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use Tests\TestCase;
use App\Http\Controllers\API\ContactController;
use App\Constants\Responses as ResponseMessage;

class ContactTest extends TestCase
{
    use DatabaseTransactions;
    private $contactController;
    private $validSubscriber = ["name" => "Fanan Dala", "email" => "fanan123@yahoo.com"];
    private $invalidSubscriber = ["email" => "fanan123"];

    public function setUp() :void
    {
        $this->contactController = new ContactController();
        parent::setUp();
        return;
    }
   
    /**
     * Tests whether addToNewsletter works in contact controller
     *
     * @return void
     */
    public function testAddToNewsLetterSuccessful()
    {
        $request = Request::create('/api/contact/subscribe', 'POST', $this->validSubscriber);
        $response = $this->contactController->addToNewsletter($request);
        $this->assertEquals(ResponseMessage::NEWSLETTER_SUCCESS, $response->getData()->message);
        $this->assertDatabaseHas('newsletter_subscribers', $this->validSubscriber);
    }

    /**
     * Tests whether addToNewsletter fails if details are not complete
     *
     * @return void
     */
    public function testAddToNewsLetterFailForIncompleteDetails()
    {
        $request = Request::create('/api/contact/subscribe', 'POST', $this->invalidSubscriber);
        $response = $this->contactController->addToNewsletter($request);
        $this->assertEquals(ResponseMessage::INPUT_ERROR, $response->getData()->message);
        $this->assertObjectHasAttribute('name',$response->getData()->data);
        $this->assertObjectHasAttribute('email',$response->getData()->data);
        $this->assertDatabaseMissing('newsletter_subscribers', $this->invalidSubscriber);
    }

    /**
     * Tests whether addToNewsletter fails for duplicate email
     *
     * @return void
     */
    public function testAddToNewsLetterFailForDuplicateEmail()
    {
        $request = Request::create('/api/contact/subscribe', 'POST', $this->validSubscriber);
        $response = $this->contactController->addToNewsletter($request);
        $response2 = $this->contactController->addToNewsletter($request);

        $this->assertEquals(ResponseMessage::INPUT_ERROR, $response2->getData()->message);
        $this->assertObjectHasAttribute('email',$response2->getData()->data);
    }

    public function tearDown() :void
    {
        parent::tearDown();
        return;
    }
}
