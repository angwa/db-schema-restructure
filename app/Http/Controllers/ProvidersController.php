<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProvidersResource;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProvidersController extends Controller
{
    public function index()
    {
        $providers = Provider::paginate(10);
        $data = ProvidersResource::collection($providers);

        return $this->jsonResponse(HTTP_SUCCESS, 'Providers returned successfully.', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider_no' => 'required|numeric|max:200|unique:providers,provider_no',
            'provider' => 'required|string|max:200',
        ]);

        $provider = Provider::create([
            'provider_no' => $request->provider_no,
            'provider' => $request->provider
        ]);

        return $this->jsonResponse(HTTP_CREATED, 'Providers created successfully.',  new ProvidersResource($provider));
    }

    public function update(Request $request, Provider $provider)
    {
        $request->validate([
            'provider' => 'required|string|max:200',
        ]);

        $provider->update([
            'provider' => $request->provider ?? $provider->provider_no
        ]);

        return $this->jsonResponse(HTTP_SUCCESS, 'Providers updated successfully.',  new ProvidersResource($provider));
    }

    public function delete(Provider $provider)
    {
        $provider->delete();

        return $this->jsonResponse(HTTP_SUCCESS, 'Providers deleted successfully.');
    }
}
