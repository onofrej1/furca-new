<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function verifyCaptcha()
    {
        $client = new Client();

        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params'=>
                [
                    'secret'=> '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
                    'response'=>request()->get('response')
                 ]
            ]
        );

        $body = json_decode((string)$response->getBody());

        return response()->json(['success' => $body->success]);
    }
}
