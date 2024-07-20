<?php

namespace App\Http\Controllers;

use Request;
use Response;
use Validator;
use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $products = Product::all();
        return $products;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     *Requests\CreateProductRequest $request
     *
     */
    public function store()
    {
        $validator = Validator::make(Request::all(), [
            'name' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {

            return Response::json(array(
                'status' => 'error',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        } else {
            Product::create(Request::all());
            return Response::json(array(
                'status' => "ok",
            ));
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $product = Product::find($id);
        $product->update(Request::all());
        $product->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete()  ;
    }
}
