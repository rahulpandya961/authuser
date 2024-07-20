<!-- resources/views/admin/products/show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $product->name }}</div>

                <div class="card-body">
                    <p><strong>Description:</strong> {{ $product->description }}</p>
                    <p><strong>Price:</strong> ${{ $product->price }}</p>
                    <p><strong>Created at:</strong> {{ $product->created_at }}</p>
                    <p><strong>Last updated:</strong> {{ $product->updated_at }}</p>

                    <a href="{{ route('auth.admin.products.index') }}" class="btn btn-secondary">Back to Products</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
