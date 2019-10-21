<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
use App\Models\NewsletterSubscriber;
use App\Constants\Constants;
use App\Constants\Responses as ResponseMessage;

class ContactController extends Controller
{
    public function addToNewsletter(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:newsletter_subscribers',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondBadRequest(ResponseMessage::INPUT_ERROR, $validator->errors()->toArray());
            }

            $data = $request->only(["name", "email"]);
            NewsletterSubscriber::create($data);
            return $this->respondWithSuccess(ResponseMessage::NEWSLETTER_SUCCESS);
        } catch (\Exception $exception) {
            if (app()->isLocal() || app()->runningUnitTests()){
                return $this->respondInternalError(ResponseMessage::SERVER_ERROR . " Error: " . $exception->getMessage());
            }
            return $this->respondInternalError(ResponseMessage::SERVER_ERROR);
        }
    }

    public function addContactMessage(Request $request)
    {
        //add contact message to database
        //send email to contact@ijayamanagement
    }
}
