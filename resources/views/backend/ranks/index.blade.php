@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="container mt-5">
            <div class="row mb-3">
                <div class="col"></div>
                <div class="col text-right">
                    <a href="{{ route('Ranks.create') }}" class="btn btn-primary mb-3">Create New Rank</a>
                </div>
            </div>
            <div class="row">
                @foreach ($ranks as $rank)
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-header">
                                <img src="{{ static_asset('Web-Images/' . $rank->image) }}" alt="{{ $rank->name }}" class="img-fluid">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $rank->name }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Minimum Price: ${{ $rank->min }}</h6>
                                <p class="card-text">Max Price: ${{ $rank->max }}</p>
                                <p class="card-text">Pts: {{ $rank->pts }}</p>
                                <a href="{{ route('Ranks.edit', $rank->id) }}" class="btn btn-success">Edit</a>
                                <form action="{{ route('Ranks.destroy', $rank->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
