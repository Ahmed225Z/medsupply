@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('All Customers') }}</h1>
        </div>
    </div>

    <div class="card">
        <form class="" id="sort_customers" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6">{{ translate('Customers') }}</h5>
                </div>

                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{ translate('Bulk Action') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item confirm-alert" href="javascript:void(0)"
                            data-target="#bulk-delete-modal">{{ translate('Delete selection') }}</a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type email or name & Enter') }}">
                    </div>
                </div>
            </div>

            <div class="card-body" style="overflow: scroll !important; max-height: 500px">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <!--<th data-breakpoints="lg">#</th>-->
                            <th>
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                            <th>{{ translate('Name') }}</th>
                            <th data-breakpoints="lg">{{ translate('Email Address') }}</th>
                            <th data-breakpoints="lg">{{ translate('Phone') }}</th>
                            <th data-breakpoints="lg">{{ translate('Package') }}</th>
                            <th style="white-space: nowrap" data-breakpoints="lg">{{ translate('Wallet Balance') }}</th>
                            <th data-breakpoints="lg">{{ translate('Website') }}</th>
                            <th data-breakpoints="lg">{{ translate('Specialties') }}</th>
                            <th style="white-space: nowrap" data-breakpoints="lg">{{ translate('Business License') }}</th>
                            <th style="white-space: nowrap" data-breakpoints="lg">{{ translate('Tax Identification') }}
                            </th>
                            <th style="white-space: nowrap" data-breakpoints="lg">{{ translate('Operating Hours') }}</th>
                            <th style="white-space: nowrap" data-breakpoints="lg">{{ translate('Contact Name') }}</th>
                            <th style="white-space: nowrap" data-breakpoints="lg">{{ translate('Contact Position') }}</th>
                            <th style="white-space: nowrap" data-breakpoints="lg">{{ translate('Contact Phone') }}</th>
                            <th data-breakpoints="lg">{{ translate('Contact Email') }}</th>
                            <th data-breakpoints="lg">{{ translate('Approve') }}</th> <!-- إضافة العمود هنا -->
                            <th class="text-center">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            @if ($user != null)
                                <tr>
                                    <!--<td>{{ $key + 1 + ($users->currentPage() - 1) * $users->perPage() }}</td>-->
                                    <td>
                                        <div class="form-group">
                                            <div class="aiz-checkbox-inline">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-one" name="id[]"
                                                        value="{{ $user->id }}">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($user->banned == 1)
                                            <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                        @endif {{ $user->name }}
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        @if ($user->customer_package != null)
                                            {{ $user->customer_package->getTranslation('name') }}
                                        @endif
                                    </td>
                                    <td>{{ single_price($user->balance) }}</td>
                                    <td>{{ $user->website }}</td>
                                    <td>{{ $user->specialties }}</td>
                                    <td>
                                        @if ($user->business_license)
                                            @php
                                                // الحصول على اسم الملف من المسار
                                                $fileName = basename($user->business_license);
                                            @endphp
                                            <a href="{{ route('users.download_license', $user->id) }}"
                                                title="{{ $fileName }}">
                                                {{ $fileName }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $user->tax_identification }}</td>
                                    <td>{{ $user->operating_hours }}</td>
                                    <td>{{ $user->contact_name }}</td>
                                    <td>{{ $user->contact_position }}</td>
                                    <td>{{ $user->contact_phone }}</td>
                                    <td>{{ $user->contact_email }}</td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_approved(this)" value="{{ $user->id }}"
                                                type="checkbox" <?php if ($user->approve_user == 1) {
                                                    echo 'checked';
                                                } ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>


                                    <td class="text-right d-flex">
                                        @can('login_as_customer')
                                            <a href="{{ route('customers.login', encrypt($user->id)) }}"
                                                class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                title="{{ translate('Log in as this Customer') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                        @endcan
                                        @can('ban_customer')
                                            @if ($user->banned != 1)
                                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                                    onclick="confirm_ban('{{ route('customers.ban', encrypt($user->id)) }}');"
                                                    title="{{ translate('Ban this Customer') }}">
                                                    <i class="las la-user-slash"></i>
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                                    onclick="confirm_unban('{{ route('customers.ban', encrypt($user->id)) }}');"
                                                    title="{{ translate('Unban this Customer') }}">
                                                    <i class="las la-user-check"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        @can('delete_customer')
                                            <a href="#"
                                                class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('customers.destroy', $user->id) }}"
                                                title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </a>
                                        @endcan
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            title="{{ translate('download License') }}"
                                            href="{{ route('users.download_license', $user->id) }}">
                                            <i class="las la-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $users->appends(request()->input())->links() }}
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="confirm-ban">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{ translate('Confirmation') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ translate('Do you really want to ban this Customer?') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <a type="button" id="confirmation" class="btn btn-primary">{{ translate('Proceed!') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-unban">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{ translate('Confirmation') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ translate('Do you really want to unban this Customer?') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <a type="button" id="confirmationunban" class="btn btn-primary">{{ translate('Proceed!') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        function sort_customers(el) {
            $('#sort_customers').submit();
        }

        function confirm_ban(url) {
            if ('{{ env('DEMO_MODE') }}' == 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            $('#confirm-ban').modal('show', {
                backdrop: 'static'
            });
            document.getElementById('confirmation').setAttribute('href', url);
        }

        function confirm_unban(url) {
            if ('{{ env('DEMO_MODE') }}' == 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            $('#confirm-unban').modal('show', {
                backdrop: 'static'
            });
            document.getElementById('confirmationunban').setAttribute('href', url);
        }

        function bulk_delete() {
            var data = new FormData($('#sort_customers')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-customer-delete') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
    <script type="text/javascript">
        function update_approved(el) {
            if ('{{ env('DEMO_MODE') }}' == 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            var status = el.checked ? 1 : 0;

            $.post('{{ route('users.toggleApprove') }}', {
                _token: '{{ csrf_token() }}',
                user_id: el.value, // تأكد من إرسال user_id بدلاً من id
                approve_user: status // تأكد من إرسال approve بدلاً من status
            }, function(data) {
                if (data.success) {
                    AIZ.plugins.notify('success', '{{ translate('Approved sellers updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
