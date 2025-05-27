@extends('frontend.layouts.user_panel')

@section('panel_content')
    <!-- Order id -->
    <div class="aiz-titlebar mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="fs-20 fw-700 text-dark">{{ translate('Order id') }}: {{ $order->code }}</h1>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="card rounded-0 shadow-none border mb-4">
        <div class="card-header border-bottom-0">
            <h5 class="fs-16 fw-700 text-dark mb-0">{{ translate('Order Summary') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-lg-6">
                    <table class="table-borderless table">
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Order Code') }}:</td>
                            <td>{{ $order->code }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Customer') }}:</td>
                            <td>{{ optional(json_decode($order->shipping_address, false))->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Email') }}:</td>
                            @if ($order->user_id != null)
                                <td>{{ $order->user->email }}</td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Shipping address') }}:</td>
                            @php
                                $shipping_address = json_decode($order->shipping_address, false);
                            @endphp
                            <td>
                                {{ $shipping_address->address ?? '' }},
                                {{ $shipping_address->city ?? '' }},
                                @if (isset($shipping_address->state))
                                    {{ $shipping_address->state }} -
                                @else
                                @endif
                                {{ $shipping_address->postal_code ?? '' }},
                                {{ $shipping_address->country ?? '' }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table class="table-borderless table">
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Order date') }}:</td>
                            <td>{{ date('d-m-Y H:i A', $order->date) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Order status') }}:</td>
                            <td>{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Total order amount') }}:</td>
                            <td>{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Shipping method') }}:</td>
                            <td>{{ translate('Flat shipping rate') }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Payment method') }}:</td>
                            <td>{{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold">{{ translate('Additional Info') }}</td>
                            <td class="">{{ $order->additional_info ?? '' }}</td>
                        </tr>
                        @if ($order->tracking_code)
                            <tr>
                                <td class="w-50 fw-600">{{ translate('Tracking code') }}:</td>
                                <td>{{ $order->tracking_code }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details -->
    <div class="row gutters-16">
        <div class="col-md-9">
            <div class="card rounded-0 shadow-none border mt-2 mb-4">
                <div class="card-header border-bottom-0">
                    <h5 class="fs-16 fw-700 text-dark mb-0">{{ translate('Order Details') }}</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="aiz-table table">
                        <thead class="text-gray fs-12">
                            <tr>
                                <th class="pl-0">#</th>
                                <th width="30%">{{ translate('Product') }}</th>
                                <th data-breakpoints="md">{{ translate('Variation') }}</th>
                                <th>{{ translate('Quantity') }}</th>
                                <th data-breakpoints="md">{{ translate('Delivery Type') }}</th>
                                <th>{{ translate('Price') }}</th>
                                <th data-breakpoints="md">{{ translate('Refund') }}</th>
                                <th data-breakpoints="md" class="text-right pr-0">{{ translate('Review') }}</th>
                            </tr>
                        </thead>
                        <tbody class="fs-14">
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td class="pl-0">{{ sprintf('%02d', $key + 1) }}</td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}"
                                                target="_blank">{{ $orderDetail->product->getTranslation('name') }}</a>
                                        @elseif($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                target="_blank">{{ $orderDetail->product->getTranslation('name') }}</a>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $orderDetail->variation ?? '' }}
                                    </td>
                                    <td>
                                        {{ $orderDetail->quantity ?? '' }}
                                    </td>
                                    <td>
                                        @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                            {{ translate('Home Delivery') }}
                                        @elseif ($order->shipping_type == 'pickup_point')
                                            @if ($order->pickup_point != null)
                                                {{ $order->pickup_point->name }} ({{ translate('Pickup Point') }})
                                            @else
                                                {{ translate('Pickup Point') }}
                                            @endif
                                        @elseif($order->shipping_type == 'carrier')
                                            @if ($order->carrier != null)
                                                {{ $order->carrier->name }} ({{ translate('Carrier') }})
                                                <br>
                                                {{ translate('Transit Time') . ' - ' . $order->carrier->transit_time }}
                                            @else
                                                {{ translate('Carrier') }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="fw-700">{{ single_price($orderDetail->price) }}</td>
                                        @php
                                            $no_of_max_day = get_setting('refund_request_time');
                                            $last_refund_date = $orderDetail->created_at->addDays($no_of_max_day);
                                            $today_date = Carbon\Carbon::now();
                                        @endphp
                                        <td>
                                            @if (
                                                $orderDetail->product != null &&
                                                    $orderDetail->product->refundable != 0 &&
                                                    $orderDetail->refund_request == null &&
                                                    $today_date <= $last_refund_date &&
                                                    $orderDetail->payment_status == 'paid' &&
                                                    $orderDetail->delivery_status == 'delivered')
                                                <a href="{{ route('refund_request_send_page', $orderDetail->id) }}"
                                                    class="btn btn-primary btn-sm rounded-0">{{ translate('Send') }}</a>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 0)
                                                <b class="text-info">{{ translate('Pending') }}</b>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 2)
                                                <b class="text-success">{{ translate('Rejected') }}</b>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 1)
                                                <b class="text-success">{{ translate('Approved') }}</b>
                                            @elseif ($orderDetail->product->refundable != 0)
                                                <b>{{ translate('') }}</b>
                                            @else
                                                <b>{{ translate('Non-refundable') }}</b>
                                            @endif
                                        </td>
                                
                                    <td class="text-xl-right pr-0">
                                        @if ($orderDetail->delivery_status == 'delivered')
                                            <a href="javascript:void(0)" class="text-info" data-toggle="modal"
                                                data-target="#review_modal"
                                                data-product-id="{{ $orderDetail->product_id }}"
                                                data-product-name="{{ $orderDetail->product->getTranslation('name') }}">
                                                <i class="las la-star"></i>
                                            </a>
                                        @else
                                            <strong>{{ translate('Not delivered yet') }}</strong>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.col-md-9 -->

        <div class="col-md-3">
            <div class="card rounded-0 shadow-none border mt-2 mb-4">
                <div class="card-header border-bottom-0">
                    <h5 class="fs-16 fw-700 text-dark mb-0">{{ translate('Order Summary') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table-borderless table">
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Subtotal') }}:</td>
                            <td>{{ single_price($order->orderDetails->sum('price')) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Tax') }}:</td>
                            <td>{{ single_price($order->orderDetails->sum('tax')) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Shipping cost') }}:</td>
                            <td>{{ single_price($order->shipping_cost) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Discount') }}:</td>
                            <td>{{ single_price($order->discount) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Total') }}:</td>
                            <td>{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax') + $order->shipping_cost - $order->discount) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
