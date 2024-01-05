<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $data   = Movie::latest()->paginate(5);

        return new ApiResource(true, 'List Data', $data);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'title'         => 'required',
            'description'   => 'required',
            'rating'        => 'required|numeric|between:0,99.99',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image  = $req->file('image');
        $image->storeAs('public/movie', $image->hashName());

        $data   = Movie::create([
                            'title'         => $req->title,
                            'description'   => $req->description,
                            'rating'        => $req->rating,
                            'image'         => $image->hashName(),
                        ]);

        return new ApiResource(true, 'Data Berhasil Ditambahkan', $data);
    }

    public function show($id)
    {
        $data   = Movie::find($id);

        return new ApiResource(true, 'Detail Data', $data);
    }

    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'title'         => 'required',
            'description'   => 'required',
            'rating'        => 'required|numeric|between:0,99.99',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = Movie::find($id);

        if ($req->hasFile('image')) {
            
            $image = $req->file('image');
            $image->storeAs('public/movie', $image->hashName());

            Storage::delete('public/movie/'.basename($data->image));

            $data->update([
                'title'         => $req->title,
                'description'   => $req->description,
                'rating'        => $req->rating,
                'image'         => $image->hashName(),
            ]);

        } else {
            $data->update([
                'title'         => $req->title,
                'description'   => $req->description,
                'rating'        => $req->rating,
            ]);
        }

        return new ApiResource(true, 'Data Berhasil Diubah', $data);
    }

    public function destroy($id)
    {
        $data = Movie::find($id);

        Storage::delete('public/movie/'.basename($data->image));

        $data->delete();

        return new ApiResource(true, 'Data Berhasil Dihapus', null);
    }
}
