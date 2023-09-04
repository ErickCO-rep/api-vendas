<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Validation\Rule;

class SellersController extends Controller
{
    public function __construct(private Seller $seller){
        $this->seller = $seller;
    }

    public function createSellers(Request $request){

        // Validates data for insertion of new seller
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('sellers', 'email'),
                ],
            ]);

            // $request['commission'] = 0;
         
            
            $sellerCreated = $this->seller->create($request->all());
            
            return response()->json(['Seller' => ['id' => $sellerCreated['id'],
                                                  'name' => $sellerCreated['name'],
                                                  'email' => $sellerCreated['email']] ], 201);
                
        } catch (\Illuminate\Validation\ValidationException $e) {

            $errors = $e->validator->errors()->toArray();
            return response()->json(['errors' =>  $errors], 422);

        }
    
    }

    public function returnSellers(){
        
        $sellers = Seller::all();

        if ($sellers->isEmpty()) {
            return response()->json(['error' => 'No sellers found.'], 404);
        }    

        $sellersWithCommissionSum = [];

        foreach ($sellers as $seller) {
            $totalCommission = $seller->sales->sum('commission');
          
            $sellersWithCommissionSum[] = [
                'id' => $seller->id,
                'name' => $seller->name,
                'email' => $seller->email,
                'commission' => $totalCommission,
            ];
        }

        return response()->json(['sellers' => $sellersWithCommissionSum], 200);

    }

}
