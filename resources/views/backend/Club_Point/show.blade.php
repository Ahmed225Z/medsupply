@extends('backend.layouts.app')

@section('content')
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-0 h6">{{ translate('Set Currency Points ') }}</h5>
        </div>
    </div>
    @php
$defaut_currency=App\Models\BusinessSetting::where('type','system_default_currency')->first();
$system_defaut_currency=$defaut_currency->value;
$system_defaut_currency_id=App\Models\Currency::where('id',$system_defaut_currency)->first();
$system_defaut_currency_symbol=$system_defaut_currency_id->symbol;
 @endphp
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                @if ($convert_point)
                    <!-- Form for editing if data exists -->
                    <form action="{{ route('update.convert.points', $convert_point->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('Put')
                        <div class="form-group">
                            <label for="number_convert">Set Points For {{$system_defaut_currency_symbol}} (1):</label>
                            <input type="number" name="number_convert" id="number_convert" class="form-control"
                                value="{{ $convert_point->convert_price }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                @else
                    <!-- Form for adding new data if no data exists -->
                    <form action="{{ route('store.convert.points') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="number_convert">Set Points For {{$system_defaut_currency_symbol}} (1):</label>
                            <input type="number" name="number_convert" id="number_convert" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                @endif

            </div>
        </div>
    </div>
@endsection
