@extends('layouts.app')
@section('title', 'Show ' . $query->customer->name . ' Transaction on ' . date('F j, Y', strtotime($query->transaction_time)))

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('sales-orders.index') }}">Sales Orders</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('sales-orders.show', $query->id) }}">
        {{ $query->customer->name }}
        <div class="small">{{ date('F j, Y', strtotime($query->transaction_time)) }}</div>
    </a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="float-start card-header-title">Sales order information</h4>

                <span class="float-end badge {{ $query->status->background_color . ' ' . $query->status->font_color }}" style="min-width: 100px;">
                    <span class="legend-indicator {{ str_replace('soft-', '', $query->status->background_color) }}"></span>
                    {{ $query->status->name }}
                </span>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5 mb-4">
                        <div class="row">
                            <div class="col-sm-12 mb-4">
                                <label for="time" class="form-label">Time</label>

                                <div>{{ date('F j, Y', strtotime($query->transaction_time)) }}</div>
                            </div>

                            <div class="col-sm-12 mb-4">
                                <label for="order-deadline" class="form-label">Order Deadline</label>

                                <div>{{ date('F j, Y', strtotime($query->order_deadline)) }}</div>
                            </div>
                        </div>

                        <div class="col-sm-12 mb-4">
                            <label for="payment-term" class="form-label">Payment Term</label>

                            <div>{!! $query->paymentTerm ? $query->paymentTerm->name : '<span class="text-muted fst-italic">Empty</span>' !!}</div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 mb-4">
                                <label for="internal-code" class="form-label">Code</label>

                                <div>{!! $query->code ?? '<span class="text-muted fst-italic">Empty</span>' !!}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 mb-4">
                                <label for="internal-code" class="form-label">Internal Code</label>

                                <div>{!! $query->internal_code ?? '<span class="text-muted fst-italic">Empty</span>' !!}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-1 mb-4">
                    </div>

                    <div class="col-sm-6 mb-4">
                        <div class="row">
                            <div class="col-sm-12 mb-4">
                                <label for="customer" class="form-label">Customer</label>

                                <div>{!! $query->customer ? $query->customer->name : '<span class="text-muted fst-italic">Empty</span>' !!}</div>
                            </div>

                            <div class="col-sm-12 mb-4">
                                <label for="customer-address" class="form-label">Customer Address</label>

                                @if($query->customerAddress)
                                <div>
                                    <div class="h4 w-100 mb-1">{{ $query->customerAddress->name }}</div>
                                    <div>{!! $query->customerAddress->phone ?? '<span class="text-muted fst-italic">-</span>' !!}</div>
                                    <div class="text-break">{!! $query->customerAddress->full_address ?? '<span class="text-muted fst-italic">-</span>' !!}</div>
                                </div>
                                @else
                                <span class="text-muted fst-italic">Empty</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label for="note" class="form-label">Note</label>

                        <div>{!! $query->note ?? '<span class="text-muted fst-italic">Empty</span>' !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title float-start">Sales Order</h4>

                @if ($query->status->name == 'Draft')
                <button class="btn-items-create btn btn-sm btn-soft-success float-end">
                    <i class="bi bi-file-earmark-plus"></i>
                    Create
                </button>
                @endif
            </div>

            <div class="card-body p-0">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="datatable-custom table-responsive">
                            <table id="datatableSalesOrder"
                                class="js-datatable table table-sm table-bordered table-hover table-thead-bordered table-nowrap table-align-middle card-table w-100"
                                data-hs-datatables-options='{
                                    "orderCellsTop": true,
                                    "isResponsive": true,
                                    "isShowPaging": false,
                                    "deferRender": true,
                                    "processing": true,
                                    "serverSide": true,
                                    "ajax": "{{ route("sales-orders.items.index", ["sales_order" => $query->id]) }}",
                                    "columns": [
                                        {
                                            "data": "actions",
                                            "name": "actions",
                                            "orderable": false,
                                            "searchable": false,
                                            "className": "text-center"
                                        },
                                        {
                                            "data": "item.name",
                                            "name": "item.name"
                                        },
                                        {
                                            "data": "quantity",
                                            "name": "quantity",
                                            "orderable": false,
                                            "searchable": false,
                                            "className": "text-end"
                                        },
                                        {
                                            "data": "price",
                                            "name": "price",
                                            "orderable": false,
                                            "searchable": false,
                                            "className": "text-end"
                                        },
                                        {
                                            "data": "total_price",
                                            "name": "total_price",
                                            "orderable": false,
                                            "searchable": false,
                                            "className": "text-end"
                                        },
                                        {
                                            "data": "note",
                                            "name": "note",
                                            "orderable": false,
                                            "searchable": false
                                        }
                                    ],
                                    "order": [
                                        [1, "asc"]
                                    ]
                                }'>
                                <thead class="thead-light">
                                    <tr>
                                        <th rowspan="1" colspan="1">Actions</th>
                                        <th rowspan="1" colspan="1" style="min-width: 20rem;">Name</th>
                                        <th rowspan="1" colspan="1" style="min-width: 10rem;">Quantity</th>
                                        <th rowspan="1" colspan="1" style="min-width: 15rem;">Price</th>
                                        <th rowspan="1" colspan="1" style="min-width: 15rem;">Total Price</th>
                                        <th rowspan="1" colspan="1" style="min-width: 20rem;;">Note</th>
                                    </tr>
                                </thead>
                                <tfoot class="thead-light">
                                    <tr>
                                        <th rowspan="1" colspan="3"></th>
                                        <th rowspan="1" colspan="1" class="text-end h1">Total Sales</th>
                                        <th rowspan="1" colspan="1" class="text-end">
                                            <span name="sales_order[total_sales]" class="h1" <?= ($query->status->name == 'Draft' ? 'style="padding: 0rem 1rem;"' : '') ?>>{{ number_format($query->total_sales, 0, '.', ',') }}</span>
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm"></div>
                    <div class="col-sm-auto">
                        @if ($query->status->name == 'Draft')
                        <button class="btn-items-create btn btn-sm btn-soft-success float-end">
                            <i class="bi bi-file-earmark-plus"></i>
                            Create
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="position-fixed start-50 bottom-0 translate-middle-x w-100 zi-99 mb-3" style="max-width: 40rem;">
    <div class="card card-sm bg-dark border-dark mx-2">
        <div class="card-body">
            <div class="row justify-content-center justify-content-sm-between">
                <div class="col">
                    @if ($query->status->name == 'Draft')
                    <button type="button" class="btn btn-ghost-danger btn-destroy">Delete</button>
                    @endif
                </div>

                <div class="col-auto">
                    <div class="d-flex gap-3">
                        @if ($query->status->name == 'Draft')
                        <button type="button" class="btn btn-ghost-light btn-discard">Discard</button>
                        <div class="btn-group" role="group">
                            <span class="btn btn-white">
                                More
                            </span>

                            <div class="btn-group">
                                <button type="button" class="btn btn-white btn-icon dropdown-toggle dropdown-toggle-empty h-100" id="datatableMore-{{ $query->id }}" data-bs-toggle="dropdown" aria-expanded="false"></button>

                                <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="datatableMore-{{ $query->id }}">
                                    <span class="dropdown-header">Options</span>
                                    <div class="dropdown-divider"></div>
                                    @forelse($statuses as $status)
                                    @if($query->status->name != $status['name'])
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="{{ $status['id'] }}" data-name="{{ $status['name'] }}">
                                        {{ $status['name'] }}
                                    </a>
                                    @endif
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-soft-warning btn-edit">Edit</button>
                        <button type="button" class="btn btn-primary btn-save">Save</button>
                        @else
                        <a class="btn btn-ghost-light btn-discard" href="{{ route('sales-orders.index') }}">Discard</a>
                        <div class="btn-group" role="group">
                            <span class="btn btn-white">
                                More
                            </span>

                            <div class="btn-group">
                                <button type="button" class="btn btn-white btn-icon dropdown-toggle dropdown-toggle-empty h-100" id="datatableMore-{{ $query->id }}" data-bs-toggle="dropdown" aria-expanded="false"></button>

                                <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="datatableMore-{{ $query->id }}">
                                    <span class="dropdown-header">Options</span>
                                    <div class="dropdown-divider"></div>
                                    @forelse($statuses as $status)
                                    @if($query->status->name != $status['name'])
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="{{ $status['id'] }}" data-name="{{ $status['name'] }}">
                                        {{ $status['name'] }}
                                    </a>
                                    @endif
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<!-- CSS Select -->
<link rel="stylesheet" href="{{ asset('assets/vendor/tom-select/dist/css/tom-select.bootstrap5.css') }}">
@endsection

@section('javascript')
<!-- JS DataTables -->
<script src="{{ asset('assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>

<script>
    (function () {
        HSCore.components.HSTomSelect.init('.js-select');

        HSCore.components.HSDatatables.init('.js-datatable', {
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                classMap: {
                    checkAll: '#datatableCheckAll',
                    counter: '#datatableCounter',
                    counterInfo: '#datatableCounterInfo'
                }
            },
            language: {
                processing: `
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading ...</span>
                    </div>
                `,
                zeroRecords: `
                    <div class="text-center p-4">
                        <img class="mb-3" src="{{ asset('assets/svg/illustrations/oc-error.svg') }}" alt="Image Description" style="width: 10rem;" data-hs-theme-appearance="default">
                        <img class="mb-3" src="{{ asset('assets/svg/illustrations-light/oc-error.svg') }}" alt="Image Description" style="width: 10rem;" data-hs-theme-appearance="dark">
                        <p class="mb-0">No data to show</p>
                    </div>
                `
            }
        });

        const datatableSalesOrder = HSCore.components.HSDatatables.getItem('datatableSalesOrder');

        datatableSalesOrder.on('draw.dt', async function (e, settings, json, xhr) {
            const listTransaction = $("#datatableSalesOrder tbody").children();
            await listTransaction.each(async function(index, transaction) {
                const thisRow = $(transaction);
                const thisId = thisRow.attr('data-id');

                if (thisId) {
                    handleGenerateItem(thisId);
                }
            });

            @if($query->status->name == 'Draft')
            handleCalculating();
            @endif
        });

        const handleSetNumber = (number) => {
            number = typeof number == 'string' ? number.replaceAll(',', '') : number;
            return Number(number) ? Number(number) : 0;
        }

        const handleNumberFormat = (number, fixedNumber=0) => {
            return handleSetNumber(number).toLocaleString('en-US', {
                minimumFractionDigits: fixedNumber, maximumFractionDigits: fixedNumber
            });
        }

        var salesOrderItem = [];
        const handleGenerateItem = async (thisId) => {
            await HSCore.components.HSTomSelect.init(`select[name="sales_order_items[${thisId}][item]"]`, {
                "valueField": 'id',
                "labelField": 'name',
                "searchField": ['name'],
                "load": function(query, callback) {
                    fetch(`{{ route("api.items.items.index") }}?owner={{ auth()->user()->parentCompany->parent_company_id }}&keyword=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(json => {
                        callback(json.data);
                    })
                    .catch(e => {
                        callback();
                    });
                },
                "render": {
                    option: function(data, escape) {
                        return `<div>${escape(data.name)}</div>`;
                    },
                    item: function(data, escape) {
                        return `<div>${escape(data.name)}</div>`;
                    }
                },
            }, `sales-order-item-${thisId}`);

            @if($query->status->name == 'Draft')
            salesOrderItem[thisId] = HSCore.components.HSTomSelect.getItem(`sales-order-item-${thisId}`);
            @endif
        }

        const handleCalculating = async () => {
            const listTransaction = $("#datatableSalesOrder tbody").children();

            var subTotal = 0;
            await listTransaction.each(async function(index, transaction) {
                const thisRow = $(transaction);
                const thisId = thisRow.attr('data-id');

                $(transaction).attr('data-quantity',    $(`[name="sales_order_items[${thisId}][quantity]"]`).val()    ?? $(transaction).attr('data-quantity'));
                $(transaction).attr('data-price',       $(`[name="sales_order_items[${thisId}][price]"]`).val()       ?? $(transaction).attr('data-price'));

                const quantity = $(transaction).attr('data-quantity');
                const price = $(transaction).attr('data-price');
                const totalPrice = quantity*price;

                $(transaction).attr('data-total-price', totalPrice)
                $(`[name="sales_order_items[${thisId}][total_price]"]`).html(handleNumberFormat(totalPrice, 0));

                subTotal += totalPrice;
            });

            $(`[name="sales_order[total_sales]"]`).html(handleNumberFormat(subTotal, 0));
        };

        $(document).on('click', '.btn-discard', async function (e) {
            const thisButton    = $(this);
            const listNote      = '';

            await $.confirm({
                title: 'Confirmation!',
                content: `Do you want to discard this form?${listNote ?? ''}`,
                autoClose: 'cancel|5000',
                type: 'orange',
                buttons: {
                    cancel: {
                        text: 'Cancel',
                        keys: ['enter', 'esc'],
                        action: function () {
                        }
                    },
                    okay: {
                        text: 'Yes, Discard',
                        btnClass: 'btn-secondary',
                        action: async function () {
                            history.back() ?? window.location.replace(`{{ route('sales-orders.index') }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.datatable-btn-status', async function (e) {
            const thisButton    = $(this);
            const thisTr        = thisButton.parentsUntil('tr').parent();
            const url           = `{{ route('sales-orders.show', $query->id) }}/status`;

            await $.confirm({
                title: 'Confirmation!',
                content: `Do you want to change status this list to be ${thisButton.data('name')}?`,
                autoClose: 'cancel|5000',
                type: 'orange',
                buttons: {
                    cancel: {
                        text: 'Cancel',
                        keys: ['esc'],
                        action: function () {
                        }
                    },
                    okay: {
                        text: 'Yes, Change',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: async function () {
                            var values          = [];
                            values['_method']   = `PUT`;
                            values['status']    = thisButton.data('id');
                            values = JSON.parse(JSON.stringify(Object.assign({}, values)));

                            $.post(url, values)
                            .done(async function(res) {
                                if (res.status == 200) {
                                    await $.confirm({
                                        title: 'Confirmation!',
                                        type: 'orange',
                                        content: `${res.message ?? ''}`,
                                        autoClose: 'close|3000',
                                        buttons: {
                                            close: {
                                                text: 'Close',
                                                keys: ['enter', 'esc'],
                                                action: function () {
                                                    location.reload();
                                                }
                                            },
                                        },
                                    });
                                } else {
                                    $.confirm({
                                        title: 'Failed',
                                        type: 'red',
                                        content: `${res.message ?? ''}`,
                                        buttons: {
                                            close: {
                                                text: 'Close',
                                                action: function () {
                                                }
                                            },
                                        }
                                    });
                                }
                            })
                            .fail(function () {
                                $.confirm({
                                    title: 'Failed',
                                    type: 'red',
                                    content: 'There is some errors in app.',
                                    autoClose: 'close|3000',
                                    buttons: {
                                        close: {
                                            text: 'Close',
                                            keys: ['enter', 'esc'],
                                            action: function () {
                                            }
                                        },
                                    }
                                });
                            });
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-edit', async function (e) {
            const thisButton    = $(this);

            await $.confirm({
                title: 'Confirmation!',
                content: `Don't you want to save this form?`,
                autoClose: 'cancel|5000',
                type: 'orange',
                buttons: {
                    cancel: {
                        text: 'Cancel',
                        keys: ['enter', 'esc'],
                        action: function () {
                        }
                    },
                    okay: {
                        text: 'Yes, Edit',
                        btnClass: 'btn-secondary',
                        action: async function () {
                            history.back() ?? window.location.replace(`{{ route('sales-orders.edit', $query->id) }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-save', async function (e) {
            const thisButton    = $(this);
            const url           = `{{ route('sales-orders.items.index', $query->id) }}`

            await $.confirm({
                title: 'Confirmation!',
                content: `Do you want to create this form?`,
                autoClose: 'cancel|5000',
                type: 'orange',
                buttons: {
                    cancel: {
                        text: 'Cancel',
                        keys: ['enter', 'esc'],
                        action: function () {
                        }
                    },
                    okay: {
                        text: 'Yes, Create',
                        btnClass: 'btn-primary',
                        action: async function () {
                            var values          = [];
                            $(`[name]`).map(function() {
                                const parameter = $(this).attr('name');

                                let value = '';
                                if ($(this).attr('type') == 'checkbox') {
                                    value = $(this).is(':checked') ? 1 : 0;
                                } else if ($(this).hasClass('form-control') || $(this).hasClass('form-select')) {
                                    value = $(this).val();
                                } else {
                                    value = $(this).attr("value") ? $(this).val() : $(this).html();
                                }
                                values[parameter] = value;
                            });
                            values = JSON.parse(JSON.stringify(Object.assign({}, values)));

                            $.post(url, values)
                            .done(async function(res) {
                                if (res.status == 200) {
                                    await $.confirm({
                                        title: 'Confirmation!',
                                        content: `${res.message ?? ''}`,
                                        type: 'orange',
                                        buttons: {
                                            edit: {
                                                text: 'Edit',
                                                btnClass: 'btn-warning',
                                                action: function () {
                                                    window.location.replace(`{{ route('sales-orders.edit', $query->id) }}`)
                                                }
                                            },
                                            close: {
                                                text: 'Still Show',
                                                btnClass: 'btn-success',
                                                keys: ['enter', 'esc'],
                                                action: function () {
                                                    datatableSalesOrder.ajax.reload(null, false);
                                                }
                                            },
                                        },
                                    });
                                } else {
                                    $.confirm({
                                        title: 'Failed',
                                        type: 'red',
                                        content: `${res.message ?? ''}`,
                                        buttons: {
                                            close: {
                                                text: 'Close',
                                                action: function () {
                                                }
                                            },
                                        }
                                    });
                                }
                            })
                            .fail(function () {
                                $.confirm({
                                    title: 'Failed',
                                    type: 'red',
                                    content: 'There is some errors in app.',
                                    autoClose: 'close|3000',
                                    buttons: {
                                        close: {
                                            text: 'Close',
                                            keys: ['enter', 'esc'],
                                            action: function () {
                                            }
                                        },
                                    }
                                });
                            });
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-destroy', async function (e) {
            const url = `{{ route('sales-orders.show', $query->id) }}`
            await $.confirm({
                title: 'Confirmation!',
                content: `Do you want to delete this form?`,
                autoClose: 'cancel|5000',
                type: 'orange',
                buttons: {
                    cancel: {
                        text: 'Cancel',
                        keys: ['enter', 'esc'],
                        action: function () {
                        }
                    },
                    destroy: {
                        text: 'Yes, Delete',
                        btnClass: 'btn-danger',
                        action: async function () {
                            $.post(url, {
                                _method: 'DELETE'
                            })
                            .done(async function(res) {
                                if (res.status == 200) {
                                    $.confirm({
                                        title: 'Success',
                                        type: 'green',
                                        content: `${res.message ?? ''}`,
                                        autoClose: 'close|3000',
                                        buttons: {
                                            close: {
                                                text: 'Close',
                                                keys: ['enter', 'esc'],
                                                action: function () {
                                                    window.location.replace(`{{ route('sales-orders.index') }}`);
                                                }
                                            },
                                        }
                                    });
                                } else {
                                    $.confirm({
                                        title: 'Failed',
                                        type: 'red',
                                        content: `${res.message ?? ''}`,
                                        buttons: {
                                            close: {
                                                text: 'Close',
                                                action: function () {
                                                }
                                            },
                                        }
                                    });
                                }
                            })
                            .fail(function () {
                                $.confirm({
                                    title: 'Failed',
                                    type: 'red',
                                    content: 'There is some errors in app.',
                                    autoClose: 'close|3000',
                                    buttons: {
                                        close: {
                                            text: 'Close',
                                            keys: ['enter', 'esc'],
                                            action: function () {
                                            }
                                        },
                                    }
                                });
                            });
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-items-create', async function (e) {   
            const listTransaction = $("#datatableSalesOrder tbody");
            const lastTransaction = listTransaction.children().last();

            var thisId = 0;
            if (lastTransaction.data('id')) {
                thisId  = handleSetNumber(lastTransaction.data('id')) + 1;
            } else {
                thisId  = 1;
                listTransaction.html('');
            }

            await listTransaction.append(`
                <tr
                    data-id="${thisId}"
                    data-operating-cost-id=""
                    data-price="0"
                    data-total-price="0"
                >
                    <td class="text-center">
                        <button type="button" class="btn-items-remove btn btn-xs btn-danger">
                            <i class="bi bi-trash3 me-1"></i>
                            Remove
                        </button>
                    </td>
                    <td>
                        <div class="tom-select-custom">
                            <select name="sales_order_items[${thisId}][item]" class="form-select form-item" autocomplete="off"
                                data-hs-tom-select-options='{
                                    "searchInDropdown": true,
                                    "hideSearch": false,
                                    "placeholder": "Search..."
                            }'></select>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend input-group-text bg-white">
                                <a class="js-minus btn btn-white btn-xs btn-icon rounded-circle ms-0 me-1" href="javascript:;">
                                    <svg width="8" height="2" viewBox="0 0 8 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 1C0 0.723858 0.223858 0.5 0.5 0.5H7.5C7.77614 0.5 8 0.723858 8 1C8 1.27614 7.77614 1.5 7.5 1.5H0.5C0.223858 1.5 0 1.27614 0 1Z" fill="currentColor" />
                                    </svg>
                                </a>
                                <a class="js-plus btn btn-white btn-xs btn-icon rounded-circle ms-1 me-0" href="javascript:;">
                                    <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 0C4.27614 0 4.5 0.223858 4.5 0.5V3.5H7.5C7.77614 3.5 8 3.72386 8 4C8 4.27614 7.77614 4.5 7.5 4.5H4.5V7.5C4.5 7.77614 4.27614 8 4 8C3.72386 8 3.5 7.77614 3.5 7.5V4.5H0.5C0.223858 4.5 0 4.27614 0 4C0 3.72386 0.223858 3.5 0.5 3.5H3.5V0.5C3.5 0.223858 3.72386 0 4 0Z" fill="currentColor" />
                                    </svg>
                                </a>
                            </div>
                            <input name="sales_order_items[${thisId}][quantity]" type="text" class="input-count form-control text-end" placeholder="" value="" autocomplete="off" style="min-width: 15rem;">
                            <div name="sales_order_items[${thisId}][unit_of_measurement]" class="input-group-append input-group-text border-0"></div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input id="price" name="sales_order_items[${thisId}][price]" type="text" class="input-count form-control text-end" placeholder="" value="" autocomplete="off" style="min-width: 10rem;">
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="form-group">
                            <span name="sales_order_items[${thisId}][total_price]" style="padding: 0rem 1rem;">0</span>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="form-group">
                            <textarea class="form-control" name="sales_order_items[${thisId}][note]" rows="1"></textarea>
                        </div>
                    </td>
                </tr>
            `);

            await handleGenerateItem(thisId);
        });

        $(document).on('click', '.btn-items-remove', async function (e) {
            const thisButton = $(this);
            await $.confirm({
                title: 'Confirmation!',
                content: `Do you want to delete this list?`,
                autoClose: 'cancel|5000',
                type: 'orange',
                buttons: {
                    cancel: {
                        text: 'Cancel',
                        keys: ['esc'],
                        action: function () {
                        }
                    },
                    destroy: {
                        text: 'Yes, Remove',
                        btnClass: 'btn-danger',
                        keys: ['enter'],
                        action: async function () {
                            thisButton.closest('tr').remove();
                        }
                    },
                }
            });

            handleCalculating();
        });

        $(document).on('change', '.form-item', async function (e) {
            const thisId = $(this).closest('tr').attr('data-id');
            const thisValue = $(`select[name="sales_order_items[${thisId}][item]"]`).val();

            let unitOfMeasurement = '';
            if (salesOrderItem[thisId].options[thisValue]?.unit_of_measurement) {
                unitOfMeasurement = salesOrderItem[thisId].options[thisValue].unit_of_measurement.code;
            } else if (salesOrderItem[thisId].options[thisValue]?.unit_of_measurement_code) {
                unitOfMeasurement = salesOrderItem[thisId].options[thisValue].unit_of_measurement_code;
            }

            $(`div[name="sales_order_items[${thisId}][unit_of_measurement]"]`).html(unitOfMeasurement);
        });

        $(document).on('input', '.input-count', async function (e) {
            handleCalculating();
        });

        $(document).on('change', '.input-count', async function (e) {
            handleCalculating();
        });

        @if($query->status->name == 'Draft')
        handleCalculating();
        @endif
    })();
</script>
@endsection