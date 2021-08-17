<?php

namespace App\Http\Controllers;

use App\Services\AddressService;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;

class AddressController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AddressService $address)
    {
        $this->service = $address;
    }

    public function fullAddress(AddressRequest $request)
    {
        try {
            $address = $this->service->requestFullAddressFromExternApi($request->getParams()->cep);
            if ($address !== null) {
                return response()->json([
                            'data' => new AddressResource($address),
                            'statusCode' => 200,
                                ], 200);
            } else {
                return response()->json([
                            'data' => new AddressResource(null),
                            'statusCode' => 404
                                ], 404);
            }
        } catch (Exception $ex) {
            return response()->json([
                        'message' => 'Erro não previsto.',
                        'error' => $ex->getMessage(),
                        'statusCode' => 500
                            ], 500);
        }
    }

}
