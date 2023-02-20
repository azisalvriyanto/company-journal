@extends('layouts.app')
@section('title', 'Create Purchase Order')

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('purchase-orders.index') }}">Purchase Orders</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('purchase-orders.show', $query->id) }}">
        {{ $query->vendor->name }}
        <div class="small">{{ date('F j, Y', strtotime($query->transaction_time)) }}</div>
    </a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('purchase-orders.edit', $query->id) }}">Edit</a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title">Purchase order information</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5 mb-4">
                        <div class="row">
                            <div class="col-sm-12 mb-4">
                                <label for="time" class="form-label">Time</label>

                                <div id="transactionDateFlatpickr" class="js-flatpickr flatpickr-custom input-group"
                                    data-hs-flatpickr-options='{
                                        "appendTo": "#transactionDateFlatpickr",
                                        "dateFormat": "F j, Y",
                                        "wrap": true
                                    }'>
                                    <div class="input-group-prepend input-group-text" data-bs-toggle>
                                        <i class="bi-calendar-week"></i>
                                    </div>

                                    <input type="text" id="time" name="time" class="flatpickr-custom-form-control form-control" placeholder="Select dates" data-input value="{{ date('F j, Y', strtotime($query->transaction_time)) }}">
                                </div>
                            </div>

                            <div class="col-sm-12 mb-4">
                                <label for="order-deadline" class="form-label">Order Deadline</label>

                                <div id="transactionOrderDeadlineFlatpickr" class="js-flatpickr flatpickr-custom input-group"
                                    data-hs-flatpickr-options='{
                                        "appendTo": "#transactionOrderDeadlineFlatpickr",
                                        "dateFormat": "F j, Y",
                                        "wrap": true
                                    }'>
                                    <div class="input-group-prepend input-group-text" data-bs-toggle>
                                        <i class="bi-calendar-week"></i>
                                    </div>

                                    <input type="text" id="order-deadline" name="order_deadline" class="flatpickr-custom-form-control form-control" placeholder="Select dates" data-input value="{{ date('F j, Y', strtotime($query->order_deadline)) }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 mb-4">
                            <label for="payment-term" class="form-label">Payment Term</label>

                            <div class="tom-select-custom">
                                <select id="payment-term" name="payment_term"
                                    class="form-select" autocomplete="off" data-hs-tom-select-options='{
                                        "searchInDropdown": true,
                                        "hideSearch": true,
                                        "placeholder": "Search..."
                                }'>

                                <option
                                    selected=""
                                    value="{{ $query->paymentTerm->id }}"
                                    data-id="{{ $query->paymentTerm->id }}"
                                    data-name="{{ $query->paymentTerm->name }}"
                                    data-deadline_value="{!! $query->paymentTerm->value !!}"
                                    data-deadline_type="{{ $query->paymentTerm->deadline_type }}"
                                ></option>
                            </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 mb-4">
                                <label for="internal-code" class="form-label">Internal Code</label>

                                <input id="internal-code" name="internal_code" type="text" class="form-control" placeholder="" value="{{ $query->internal_code }}" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-1 mb-4">
                    </div>

                    <div class="col-sm-6 mb-4">
                        <div class="row">
                            <div class="col-sm-12 mb-4">
                                <label for="vendor" class="form-label">Vendor</label>

                                <div class="tom-select-custom">
                                    <select id="vendor" name="vendor" class="form-select" autocomplete="off"
                                        data-hs-tom-select-options='{
                                            "searchInDropdown": true,
                                            "hideSearch": false,
                                            "placeholder": "Search..."
                                    }'>
                                        <option
                                            selected=""
                                            value="{{ $query->vendor->id }}"
                                            data-id="{{ $query->vendor->id }}"
                                            data-name="{{ $query->vendor->name }}"
                                        ></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 mb-4">
                                <label for="vendor-address" class="form-label">Vendor Address</label>

                                <div class="tom-select-custom">
                                    <select id="vendor-address" name="vendor_address" class="form-select h-100" autocomplete="off"
                                        data-hs-tom-select-options='{
                                            "searchInDropdown": true,
                                            "hideSearch": false,
                                            "placeholder": "Search..."
                                    }' disabled="">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label for="note" class="form-label">Note</label>

                        <textarea id="note" name="note" class="form-control textarea" rows="5">{{ $query->note }}</textarea>
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
                    <button type="button" class="btn btn-ghost-danger btn-destroy">Delete</button>
                </div>

                <div class="col-auto">
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-ghost-light btn-discard">Discard</button>
                        <button type="button" class="btn btn-soft-success btn-show">Show</button>
                        <button type="button" class="btn btn-primary btn-save">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr/dist/flatpickr.min.css') }}">
@endsection

@section('javascript')
<!-- JS Dropzone -->
<script src="{{ asset('assets/vendor/dropzone/dist/min/dropzone.min.js') }}"></script>

<!-- JS FlatPickr -->
<script src="{{ asset('/assets/vendor/flatpickr/dist/flatpickr.min.js') }}"></script>

<script>
    (function () {
        HSCore.components.HSFlatpickr.init('.js-flatpickr');

        HSCore.components.HSTomSelect.init(`select[name="payment_term"]`, {
		    "valueField": 'id',
		    "labelField": 'name',
            "searchField": ['name'],
		    "options": <?= json_encode($paymentTerms) ?>,
            "render": {
                option: function(data, escape) {
                    return `<div data-deadline_value="${escape(data.value)}" data-deadline_type="${escape(data.deadline_type)}">${escape(data.name)}</div>`;
                },
                item: function(data, escape) {
                    return `<div data-deadline_value="${escape(data.value)}" data-deadline_type="${escape(data.deadline_type)}">${escape(data.name)}</div>`;
                }
            }
        });

        HSCore.components.HSTomSelect.init(`select[name="vendor"]`, {
		    "valueField": 'id',
		    "labelField": 'name',
            "searchField": ['name'],
            "load": function(query, callback) {
                fetch(`{{ route("api.vendors.index") }}?owner={{ auth()->user()->parentCompany->parent_company_id }}&keyword=${encodeURIComponent(query)}`)
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
            }
        });

        const handleGeneratingVendorAddress = async (vendorAddresses, vendorAddressId=null) => {
            HSCore.components.HSTomSelect.init(`select[name="vendor_address"]`, {
                "valueField": 'id',
                "labelField": 'full_address',
                "searchField": ['full_address'],
                "options": vendorAddresses,
                "items": vendorAddressId ? [vendorAddressId] : [],
                "render": {
                    option: function(data, escape) {
                        return `<div class="row">
                            <div class="col-sm-12">
                                <div class="h4 w-100 mb-1">${escape(data.name)}</div>
                                <div>${escape(data.phone)}</div>
                                <div class="text-truncate">${escape(data.full_address)}</div>
                            </div>
                        </div>`;
                    },
                    item: function(data, escape) {
                        return `<div class="row">
                            <div class="col-sm-12">
                                <div class="h4 w-100 mb-1">${escape(data.name)}</div>
                                <div>${escape(data.phone)}</div>
                                <div class="text-break">${escape(data.full_address)}</div>
                            </div>
                        </div>`;
                    }
                }
            });
        }

        $(document).on('change', '#vendor', async function (e) {
            $(`select[name="vendor_address"]`).prop(`disabled`, false);
            const vendor = $(`#vendor`);
            const vendorAddress = $(`#vendor-address`)[0].tomselect;
            const vendorAddresses = vendor[0].tomselect.options[vendor.val()].billing_addresses;

            if (!vendorAddress) {
                handleGeneratingVendorAddress({!! json_encode($query->vendor->billingAddresses) !!}, '<?= $query->vendorAddress->id ?>');
            } else {
                vendorAddress.clear();
                vendorAddress.clearOptions();
                vendorAddress.addOptions(vendorAddresses);
            }

        });

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
                            history.back() ?? window.location.replace(`{{ route('purchase-orders.index') }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-show', async function (e) {
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
                        text: 'Yes, Show',
                        btnClass: 'btn-secondary',
                        action: async function () {
                            history.back() ?? window.location.replace(`{{ route('purchase-orders.show', $query->id) }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-save', async function (e) {
            const thisButton    = $(this);
            const url           = `{{ route('purchase-orders.show', $query->id) }}`

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
                            values['_method']   = `PUT`;
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
                                                    window.location.replace(`{{ route('purchase-orders.show', $query->id) }}`)
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
            const url = `{{ route('purchase-orders.show', $query->id) }}`
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
                                                    window.location.replace(`{{ route('purchase-orders.index') }}`);
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

        $('#vendor').change();
    })();
</script>
@endsection