<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = Auth::user()?->addresses;
        if (!$addresses) {
            return $this->sendError('Addresses Not Found.', ['error' => 'Not Found'], 404);
        }
        $data = [];
        foreach ($addresses as $address) {
            $item = [
                'id' => $address->id,
                'type' => $address->type,
                'country' => $address->country,
                'city' => $address->city,
                'street' => $address->street,
                'house_number' => $address->house_number,
                'postal_code' => $address->postal_code,
                'note' => $address->note,
            ];
            $data[] = $item;
        }
        return $this->sendResponse($data, 'Addresses List');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required',
            'country' => 'required',
            'city' => 'required',
            'street' => 'required',
            'house_number' => 'required',
            'postal_code' => 'required',
            'note' => 'nullable',
        ]);
        $data['user_id'] = Auth::user()->id;
        $address = Address::create($data);
        $response = [
            'id' => $address->id,
            'type' => $address->type,
            'country' => $address->country,
            'city' => $address->city,
            'street' => $address->street,
            'house_number' => $address->house_number,
            'postal_code' => $address->postal_code,
            'note' => $address->note,
        ];
        return $this->sendResponse($response, 'Address Created Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        $response = [
            'id' => $address->id,
            'type' => $address->type,
            'country' => $address->country,
            'city' => $address->city,
            'street' => $address->street,
            'house_number' => $address->house_number,
            'postal_code' => $address->postal_code,
            'note' => $address->note,
        ];
        return $this->sendResponse($response, 'Address Information');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        $data = $request->validate([
            'type' => 'required',
            'country' => 'required',
            'city' => 'required',
            'street' => 'required',
            'house_number' => 'required',
            'postal_code' => 'required',
            'note' => 'nullable',
        ]);
        $data['user_id'] = Auth::user()->id;
        $address->update($data);
        $response = [
            'id' => $address->id,
            'type' => $address->type,
            'country' => $address->country,
            'city' => $address->city,
            'street' => $address->street,
            'house_number' => $address->house_number,
            'postal_code' => $address->postal_code,
            'note' => $address->note,
        ];
        return $this->sendResponse($response, 'Address Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        $address->delete();
        $response = [];
        return $this->sendResponse($response, 'Address Deleted Successfully');
    }
}
