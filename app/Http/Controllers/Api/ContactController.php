<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SaleMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make(
            request()->all(),
            [
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'case' => 'required',
                'message' => 'required'
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $contact = Contact::create($request->all());

        Mail::to('arequipacasacampo@gmail.com')->send(new SaleMail($contact));

        return response()->json(["message" => 200, "message_text" => "Mensaje enviado, en breve nos contactamos contigo"]);
    }
}
