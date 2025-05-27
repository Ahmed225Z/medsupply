@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="card shadow-none rounded-0 border">
        @php
            $convert_point = App\Models\ConvertPoint::first();
            $defaut_currency=App\Models\BusinessSetting::where('type','system_default_currency')->first();
            $system_defaut_currency=$defaut_currency->value;
            $system_defaut_currency_id=App\Models\Currency::where('id',$system_defaut_currency)->first();
            $system_defaut_currency_symbol=$system_defaut_currency_id->symbol;
        @endphp

        <div class="card-header border-bottom-0">
            <h5 class="mb-0 fs-20 fw-700 text-dark">{{ translate('Club Point') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">

                @if ($convert_point)
                    <p class="text-dark text-center bg-warning">Every {{ $convert_point->convert_price }} point will be converted into {{$system_defaut_currency_symbol}} (1)</p>
                @endif

                <thead class="text-gray fs-12">
                    <tr>
                        <th class="pl-0">#</th>
                        <th data-breakpoints="md">{{ translate('Date') }}</th>
                        <th>{{ translate('Points') }}</th>
                        <th data-breakpoints="md">{{ translate('Convert') }}</th>
                        <th data-breakpoints="md">{{ translate('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="fs-14">
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($user_point as $user_point)
                        @php
                            $i++;
                        @endphp
                        <tr>
                            <td>{{ $i }}</td>
                            <!-- Date -->
                            <td class="text-secondary">{{ $user_point->created_at->format('Y-m-d') }}</td>
                            <!-- Points -->
                            <td class="pl-0">
                                {{ $user_point->Points }}
                            </td>
                            <!-- Convert -->
                            @if ($user_point->convert == 0)
                                <td class="fw-700 text-danger">No</td>
                            @endif
                            @if ($user_point->convert == 1)
                                <td class="fw-700 text-success">Yes</td>
                            @endif
                            <!-- Options -->
                            <td class="pr-0">
                                {{-- Form Convert  --}}
                                @if ($user_point->convert == 0)
                                    <form action="{{ route('convert_into_wallet', $user_point->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-soft-secondary-base" type="submit">Convert</button>
                                    </form>
                                @else
                                    <p class="fw-700 text-success">Converted</p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination -->
            <div class="aiz-pagination mt-2">
                {{-- {{ $orders->links() }} --}}
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
@endsection
