<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use Tests\TestCase;
use App\Http\Controllers\API\ContactController;

class ContactTest extends TestCase
{
    private $contactController;
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
        $this->assertFalse(True);
    }

    /**
     * Tests whether addToNewsletter fails in if details are not complete
     *
     * @return void
     */
    public function testAddToNewsLetterFailForIncompleteDetails()
    {
        $this->assertFalse(True);
    }

    public function tearDown() :void
    {
        parent::tearDown();
        return;
    }
}
