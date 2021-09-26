<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);
        return view('store.index', compact('products', $products));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('store.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);


        $product = new Product;

        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->description = $request->description;
        $product->status = $request->status;

        if ($request->warranty == "yes") {
            $product->warranty = true;
        } else {
            $product->warranty = false;
        }

        $product->save();

        foreach ($request->image as $image) {

            $name = $image->getClientOriginalName().'.'.$image->getClientOriginalExtension();
            $url =  $image->getClientOriginalName().time().'.'.$image->getClientOriginalExtension();

            Image::make($image)->save(public_path('images/'.$url));

            $newImage = new \App\Models\Image;

            $newImage->name = $name;
            $newImage->url = $url;
            $newImage->product_id = $product->id;

            $newImage->save();
        }

        return redirect()->route('store.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $images = \App\Models\Image::all()->where('product_id', '=', $product);
        return view('store.show', compact('product','images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('store.edit', compact('product', $product));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->description = $request->description;
        $product->status = $request->status;

        if ($request->warranty == "yes") {
            $product->warranty = true;
        } else {
            $product->warranty = false;
        }

        $product->update();

        if(count($request->files) > 0) {
            $images = \App\Models\Image::all()->where('product_id', '=', $id);

            foreach ($images as $image) {
                $exists = Storage::disk('local')->exists(storage_path('images/'.$image->url));
                if ($exists) {
                    Storage::delete(public_path('storage/images/'.$image->url));
                    \App\Models\Image::destroy($image->id);
                }
            }

            foreach ($request->image as $image) {

                $name = $image->getClientOriginalName().'.'.$image->getClientOriginalExtension();
                $url =  Hash::make($image->getClientOriginalName()).'.'.$image->getClientOriginalExtension();

                Image::make($image)->save(public_path('images/'.$url));

                $newImage = new \App\Models\Image;

                $newImage->name = $name;
                $newImage->url = $url;
                $newImage->product_id = $product->id;

                $newImage->save();
            }
        }
        return redirect()->route('store.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $images = \App\Models\Image::all()->where('product_id', '=', $id);

        foreach ($images as $image) {
            $exists = Storage::disk('local')->exists(storage_path('images/'.$image->url));
            if ($exists) {
                Storage::delete(public_path('storage/images/'.$image->url));
                \App\Models\Image::destroy($image->id);
            }
        }

        Product::destroy($product->id);

        return redirect()->route('store.index');

    }
}
