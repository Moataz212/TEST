<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://localhost:5000']);
    }

    public function store(Request $request)
    {
        // بيانات العنوان
        $address_data = $request->all();

        // تشفير البيانات باستخدام واجهة API
        $response = $this->client->post('/encrypt', [
            'json' => ['data' => json_encode($address_data)]
        ]);
        $encrypted_data = json_decode($response->getBody()->getContents(), true)['encrypted_data'];

   
        Log::info('Data encrypted', ['data' => $encrypted_data]);

        $address = new Address();
        $address->user_id = $request->user()->id;
        $address->type = $encrypted_data;
        $address->country = $request->country;
        $address->city = $request->city;
        $address->street = $request->street;
        $address->house_number = $request->house_number;
        $address->postal_code = $request->postal_code;
        $address->note = $request->note;
        $address->save();

        return response()->json(['message' => 'Address stored successfully']);
    }
}