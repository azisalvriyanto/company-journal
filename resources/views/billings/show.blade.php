@extends('layouts.app')
@section('title', 'Show ' . $query->supplier->name . ' Transaction on ' . date('F j, Y', strtotime($query->transaction_time)))

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('billings.index') }}">Billings</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('billings.show', $query->id) }}">
        {{ $query->supplier->name }}
        <div class="small">{{ date('F j, Y', strtotime($query->transaction_time)) }}</div>
    </a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="float-start card-header-title">Billing information</h4>

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
                                <label for="due-time" class="form-label">Time</label>

                                <div>{{ date('F j, Y', strtotime($query->transaction_time)) }}</div>
                            </div>

                            <div class="col-sm-12 mb-4">
                                <label for="payment-term" class="form-label">Payment Term</label>

                                <div>{!! $query->paymentTerm ? $query->paymentTerm->name : '<span class="text-muted fst-italic">Empty</span>' !!}</div>
                            </div>

                            <div class="col-sm-12 mb-4">
                                <label for="due-time" class="form-label">Due Time</label>

                                <div>{{ date('F j, Y', strtotime($query->transaction_due_time)) }}</div>
                            </div>
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
                                <label for="supplier" class="form-label">Supplier</label>

                                <div>{!! $query->supplier ? $query->supplier->name : '<span class="text-muted fst-italic">Empty</span>' !!}</div>
                            </div>

                            <div class="col-sm-12 mb-4">
                                <label for="supplier-address" class="form-label">Supplier Address</label>

                                @if($query->supplierAddress)
                                <div>
                                    <div class="h4 w-100 mb-1">{{ $query->supplierAddress->name }}</div>
                                    <div>{!! $query->supplierAddress->phone ?? '<span class="text-muted fst-italic">-</span>' !!}</div>
                                    <div class="text-break">{!! $query->supplierAddress->full_address ?? '<span class="text-muted fst-italic">-</span>' !!}</div>
                                </div>
                                @else
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
                <h4 class="card-header-title float-start">Billing</h4>

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
                            <table id="datatableBilling"
                                class="js-datatable table table-sm table-bordered table-hover table-thead-bordered table-nowrap table-align-middle card-table w-100"
                                data-hs-datatables-options='{
                                    "orderCellsTop": true,
                                    "isResponsive": true,
                                    "isShowPaging": false,
                                    "deferRender": true,
                                    "processing": true,
                                    "serverSide": true,
                                    "ajax": "{{ route("billings.items.index", ["billing" => $query->id]) }}",
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
                                        <th rowspan="1" colspan="1" class="text-end h4">Subtotal</th>
                                        <th rowspan="1" colspan="1" class="text-end">
                                            <span name="billing[subtotal]" class="h4" <?= ($query->status->name == 'Draft' ? 'style="padding: 0rem 1rem;"' : '') ?>>{{ number_format($query->subtotal, 0, '.', ',') }}</span>
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                    </tr>
                                    <tr>
                                        <th rowspan="1" colspan="3"></th>
                                        <th rowspan="1" colspan="1" class="text-end">Total Shipping</th>
                                        <th rowspan="1" colspan="1" class="text-end">
                                            @if($query->status->name == 'Draft')
                                            <input name="billing[total_shipping]" type="text" class="input-count form-control text-end" placeholder="0" value="{{ number_format($query->total_shipping, 0, '.', '') }}" aria-label="0" autocomplete="off">
                                            @else
                                            <span>{{ number_format($query->total_shipping, 0, '.', ',') }}</span>
                                            @endif
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                    </tr>
                                    <tr>
                                        <th rowspan="1" colspan="3"></th>
                                        <th rowspan="1" colspan="1" class="text-end">Total Discount</th>
                                        <th rowspan="1" colspan="1" class="text-end">
                                            @if($query->status->name == 'Draft')
                                            <input name="billing[total_discount]" type="text" class="input-count form-control text-end" placeholder="0" value="{{ number_format($query->total_discount, 0, '.', '') }}" aria-label="0" autocomplete="off">
                                            @else
                                            <span>{{ number_format($query->total_discount, 0, '.', ',') }}</span>
                                            @endif
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                    </tr>
                                    @if($query->status->name == 'Draft')
                                    <tr>
                                        <th rowspan="1" colspan="3" class="pb-0"></th>
                                        <th rowspan="1" colspan="1" class="pb-0 text-end">Total Tax</th>
                                        <th rowspan="1" colspan="1" class="pb-0 text-end">
                                            <div class="tom-select-custom tom-select-custom-end">
                                                <div id="taxSelect" class="input-group">
                                                    <input name="billing[total_tax_value]" type="text" class="input-count form-control text-end" placeholder="0.00" value="{{ number_format($query->total_tax_value, 0, '.', '') }}" aria-label="0.00" autocomplete="off" style="min-width: 4rem;">
                                                    <select name="billing[total_tax_type]" class="input-count js-select form-select" data-hs-tom-select-options='{
                                                        "searchInDropdown": false,
                                                        "hideSearch": true
                                                    }'>
                                                        <option value="Flat" {{ $query->total_tax_type == 'Flat' ? 'selected' : ($query->total_tax_type == 'Percent' ? '' : 'selected') }}>Flat (Value)</option>
                                                        <option value="Percent" {{ $query->total_tax_type == 'Percent' ? 'selected' : '' }}>Percent (%)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                    </tr>
                                    <tr>
                                        <th rowspan="1" colspan="4" class="pt-0"></th>
                                        <th rowspan="1" colspan="1" class="pt-0 text-end">
                                            <span name="billing[total_tax]" style="padding: 0rem 1rem;">{{ number_format($query->total_tax, 0, '.', ',') }}</span>
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                    </tr>
                                    @else
                                    <tr>
                                        <th rowspan="1" colspan="3"></th>
                                        <th rowspan="1" colspan="1" class="text-end">Total Tax</th>
                                        <th rowspan="1" colspan="1" class="text-end">
                                            <span>{{ number_format($query->total_tax, 0, '.', ',') }}</span>
                                        </th>
                                        <th rowspan="1" colspan="1">{{ $query->total_tax_type == 'Percent' ? number_format($query->total_tax_value, 2, '.', ',') . '%' : '' }}</th>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th rowspan="1" colspan="3"></th>
                                        <th rowspan="1" colspan="1" class="text-end h2">Total Bill</th>
                                        <th rowspan="1" colspan="1" class="text-end">
                                            <span name="billing[total_bill]" class="h2" <?= ($query->status->name == 'Draft' ? 'style="padding: 0rem 1rem;"' : '') ?>>{{ number_format($query->total_bill, 0, '.', ',') }}</span>
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                    </tr>
                                    <tr hidden="">
                                        <th rowspan="1" colspan="3"></th>
                                        <th rowspan="1" colspan="1" class="text-end">Amount Paid</th>
                                        <th rowspan="1" colspan="1" class="text-end">
                                            <span name="billing[total_amount_paid]" <?= ($query->status->name == 'Draft' ? 'style="padding: 0rem 1rem;"' : '') ?>>{{ number_format($query->total_amount_paid, 0, '.', ',') }}</span>
                                        </th>
                                        <th rowspan="1" colspan="1"></th>
                                    </tr>
                                    <tr hidden="">
                                        <th rowspan="1" colspan="3"></th>
                                        <th rowspan="1" colspan="1" class="text-end h1">Due Balance</th>
                                        <th rowspan="1" colspan="1" class="text-end">
                                            <span name="billing[total_due_balance]" class="h1" <?= ($query->status->name == 'Draft' ? 'style="padding: 0rem 1rem;"' : '') ?>>{{ number_format($query->total_due_balance, 0, '.', ',') }}</span>
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
                                    @if($query->status->name != 'Draft')
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="{{ $statuses['Draft']['id'] }}" data-name="Draft">
                                        <i class="bi bi-file-earmark-lock dropdown-item-icon"></i> Draft
                                    </a>
                                    @endif
                                    @if($query->status->name != 'Cancel')
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="{{ $statuses['Cancel']['id'] }}" data-name="Cancel">
                                        <i class="bi bi-file-earmark-x dropdown-item-icon"></i> Cancel
                                    </a>
                                    @endif
                                    @if($query->status->name != 'Lock')
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="{{ $statuses['Lock']['id'] }}" data-name="Lock">
                                        <i class="bi bi-file-earmark-lock dropdown-item-icon"></i> Lock
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-soft-warning btn-edit">Edit</button>
                        <button type="button" class="btn btn-primary btn-save">Save</button>
                        @else
                        <a class="btn btn-ghost-light btn-discard" href="{{ route('billings.index') }}">Discard</a>
                        <div class="btn-group" role="group">
                            <span class="btn btn-white">
                                More
                            </span>

                            <div class="btn-group">
                                <button type="button" class="btn btn-white btn-icon dropdown-toggle dropdown-toggle-empty h-100" id="datatableMore-{{ $query->id }}" data-bs-toggle="dropdown" aria-expanded="false"></button>

                                <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="datatableMore-{{ $query->id }}">
                                    <span class="dropdown-header">Options</span>
                                    <div class="dropdown-divider"></div>
                                    @if($query->status->name != 'Draft')
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="{{ $statuses['Draft']['id'] }}" data-name="Draft">
                                        <i class="bi bi-file-earmark-lock dropdown-item-icon"></i> Draft
                                    </a>
                                    @endif
                                    @if($query->status->name != 'Lock')
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="{{ $statuses['Lock']['id'] }}" data-name="Lock">
                                        <i class="bi bi-file-earmark-lock dropdown-item-icon"></i> Lock
                                    </a>
                                    @endif
                                    @if($query->status->name != 'Cancel')
                                    <a class="dropdown-item datatable-btn-status" href="javascript:;" data-id="{{ $statuses['Cancel']['id'] }}" data-name="Cancel">
                                        <i class="bi bi-file-earmark-x dropdown-item-icon"></i> Cancel
                                    </a>
                                    @endif
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
        HSCore.components.HSFlatpickr.init('.js-flatpickr');

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

        const datatableBilling = HSCore.components.HSDatatables.getItem('datatableBilling');

        datatableBilling.on('draw.dt', async function (e, settings, json, xhr) {
            const listTransaction = $("#datatableBilling tbody").children();
            await listTransaction.each(async function(index, transaction) {
                const thisRow = $(transaction);
                const thisId = thisRow.attr('data-id');

                handleGenerateItem(thisId);
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

        var billingItem = [];
        const handleGenerateItem = async (thisId, options=null, items=null) => {
            HSCore.components.HSTomSelect.init(`select[name="billing_items[${thisId}][item]"]`, {
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
            }, `billing-item-${thisId}`);

            @if($query->status->name == 'Draft')
            billingItem[thisId] = HSCore.components.HSTomSelect.getItem(`billing-item-${thisId}`);
            @endif
        }

        const handleCalculating = async () => {
            const listTransaction = $("#datatableBilling tbody").children();

            var subTotal = 0;
            await listTransaction.each(async function(index, transaction) {
                const thisRow = $(transaction);
                const thisId = thisRow.attr('data-id');

                $(transaction).attr('data-quantity',    $(`[name="billing_items[${thisId}][quantity]"]`).val()    ?? $(transaction).attr('data-quantity'));
                $(transaction).attr('data-price',       $(`[name="billing_items[${thisId}][price]"]`).val()       ?? $(transaction).attr('data-price'));

                const quantity = $(transaction).attr('data-quantity');
                const price = $(transaction).attr('data-price');
                const totalPrice = quantity*price;

                $(transaction).attr('data-total-price', totalPrice)
                $(`[name="billing_items[${thisId}][total_price]"]`).html(handleNumberFormat(totalPrice, 0));

                subTotal += totalPrice;
            });

            $(`[name="billing[subtotal]"]`).html(handleNumberFormat(subTotal, 0));

            const totalShipping = handleSetNumber($(`[name="billing[total_shipping]"]`).val());
            const totalDiscount = handleSetNumber($(`[name="billing[total_discount]"]`).val());
            subTotal = subTotal + totalShipping - totalDiscount;

            const totalTaxValue = handleSetNumber($(`[name="billing[total_tax_value]"]`).val());
            const totalTaxType = $(`[name="billing[total_tax_type]"]`).val();
            const totalTax = handleSetNumber(totalTaxType == 'Percent' ? subTotal*(totalTaxValue/100) : totalTaxValue);
            if (totalTaxType == 'Percent') {
                $(`[name="billing[total_tax]"]`).html(handleNumberFormat(totalTax, 0));
            } else {
                $(`[name="billing[total_tax]"]`).html('');
            }

            const totalBill = subTotal + totalTax;
            $(`[name="billing[total_bill]"]`).html(handleNumberFormat(totalBill, 0));

            const totalAmountPaid = handleSetNumber($(`[name="billing[total_amount_paid]"]`).html());
            $(`[name="billing[total_due_balance]"]`).html(handleNumberFormat(totalBill - totalAmountPaid, 0));
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
                            history.back() ?? window.location.replace(`{{ route('billings.index') }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.datatable-btn-status', async function (e) {
            const thisButton    = $(this);
            const thisTr        = thisButton.parentsUntil('tr').parent();
            const url           = `{{ route('billings.show', $query->id) }}/status`;

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
                            history.back() ?? window.location.replace(`{{ route('billings.edit', $query->id) }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-save', async function (e) {
            const thisButton    = $(this);
            const url           = `{{ route('billings.items.index', ['billing' => $query->id]) }}`;

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
                                            show: {
                                                text: 'Show',
                                                btnClass: 'btn-primary',
                                                action: function () {
                                                    window.location.replace(`{{ route('billings.show', $query->id) }}`)
                                                }
                                            },
                                            close: {
                                                text: 'Still Edit',
                                                btnClass: 'btn-success',
                                                keys: ['enter', 'esc'],
                                                action: function () {
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
            const url = `{{ route('billings.show', $query->id) }}`
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
                                                    window.location.replace(`{{ route('billings.index') }}`);
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
            const listTransaction = $("#datatableBilling tbody");
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
                            <select name="billing_items[${thisId}][item]" class="form-select form-item" autocomplete="off"
                                data-hs-tom-select-options='{
                                    "searchInDropdown": true,
                                    "hideSearch": false,
                                    "placeholder": "Search..."
                            }'></select>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-merge">
                            <input name="billing_items[${thisId}][quantity]" type="text" class="input-count form-control text-end" placeholder="" value="" autocomplete="off" style="min-width: 10rem;">
                            <div name="billing_items[${thisId}][unit_of_measurement]" class="input-group-append input-group-text"></div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input id="price" name="billing_items[${thisId}][price]" type="text" class="input-count form-control text-end" placeholder="" value="" autocomplete="off" style="min-width: 10rem;">
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="form-group">
                            <span name="billing_items[${thisId}][total_price]" style="padding: 0rem 1rem;">0</span>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="form-group">
                            <textarea class="form-control" name="billing_items[${thisId}][note]" rows="1"></textarea>
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
            const thisValue = $(`select[name="billing_items[${thisId}][item]"]`).val();

            console.log(billingItem[thisId].options[thisValue])
            let unitOfMeasurement = '';
            if (billingItem[thisId].options[thisValue]?.unit_of_measurement) {
                unitOfMeasurement = billingItem[thisId].options[thisValue].unit_of_measurement.code;
            } else if (billingItem[thisId].options[thisValue]?.unit_of_measurement_code) {
                unitOfMeasurement = billingItem[thisId].options[thisValue].unit_of_measurement_code;
            }

            $(`div[name="billing_items[${thisId}][unit_of_measurement]"]`).html(unitOfMeasurement);
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