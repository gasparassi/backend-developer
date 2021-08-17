<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressRequest extends Controller
{

    public function __construct(Request $request)
    {
        $this->validate(
                $request, [
            'cep' => 'required|integer',
                ]
        );

        parent::__construct($request);
    }

}
