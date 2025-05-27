@extends('frontend.layouts.app')
<style>
    .alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
    height: 246px;
    text-align: center;
    }
</style>
@section('content')
    <div class="container" >
        <div class="alert alert-info">
            {{ __('Your registration under review, please contact us.') }}
        </div>
    </div>
@endsection
