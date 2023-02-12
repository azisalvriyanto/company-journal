@extends('layouts.app')
@section('title', 'Create Billing')

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('billings.index') }}">Billings</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('billings.create') }}">Create Billing</a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title">Billing information</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5 mb-4">
                        <div class="row">
                            <div class="col-sm-12 mb-4">
                                <label for="due-time" class="form-label">Time</label>

                                <div id="transactionDateFlatpickr" class="js-flatpickr flatpickr-custom input-group"
                                    data-hs-flatpickr-options='{
                                        "appendTo": "#transactionDateFlatpickr",
                                        "dateFormat": "F j, Y",
                                        "wrap": true
                                    }'>
                                    <div class="input-group-prepend input-group-text" data-bs-toggle>
                                        <i class="bi-calendar-week"></i>
                                    </div>

                                    <input type="text" id="time" name="time" class="flatpickr-custom-form-control form-control" placeholder="Select dates" data-input value="{{ date('F j, Y') }}">
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
                                    }'></select>
                                </div>
                            </div>

                            <div class="col-sm-12 mb-4">
                                <label for="due-time" class="form-label">Due Time</label>

                                <div id="transactionDueDateFlatpickr" class="js-flatpickr flatpickr-custom input-group"
                                    data-hs-flatpickr-options='{
                                        "appendTo": "#transactionDueDateFlatpickr",
                                        "dateFormat": "F j, Y",
                                        "wrap": true
                                    }'>
                                    <div class="input-group-prepend input-group-text" data-bs-toggle>
                                        <i class="bi-calendar-week"></i>
                                    </div>

                                    <input type="text" id="due-time" name="due_time" class="flatpickr-custom-form-control form-control" placeholder="Select dates" data-input value="{{ date('F j, Y') }}" disabled="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 mb-4">
                                <label for="internal-code" class="form-label">Internal Code</label>

                                <input id="internal-code" name="internal_code" type="text" class="form-control" placeholder="" value="" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-1 mb-4">
                    </div>

                    <div class="col-sm-6 mb-4">
                        <div class="row">
                            <div class="col-sm-12 mb-4">
                                <label for="supplier" class="form-label">Supplier</label>

                                <div class="tom-select-custom">
                                    <select id="supplier" name="supplier" class="form-select" autocomplete="off"
                                        data-hs-tom-select-options='{
                                            "searchInDropdown": true,
                                            "hideSearch": false,
                                            "placeholder": "Search..."
                                    }'>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 mb-4">
                                <label for="supplier-address" class="form-label">Supplier Address</label>

                                <div class="tom-select-custom">
                                    <select id="supplier-address" name="supplier_address" class="form-select h-100" autocomplete="off"
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

                        <textarea id="note" name="note" class="form-control textarea" rows="5"></textarea>
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
                <div class="col"></div>

                <div class="col-auto">
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-ghost-light btn-discard">Discard</button>
                        <button type="button" class="btn btn-primary btn-create">Save</button>
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
                    return `<div data-deadline-value="${escape(data.value)}" data-deadline-type="${escape(data.deadline_type)}">${escape(data.name)}</div>`;
                },
                item: function(data, escape) {
                    return `<div data-deadline-value="${escape(data.value)}" data-deadline-type="${escape(data.deadline_type)}">${escape(data.name)}</div>`;
                }
            }
        });

        HSCore.components.HSTomSelect.init(`select[name="supplier"]`, {
		    "valueField": 'id',
		    "labelField": 'name',
            "searchField": ['name'],
		    "options": [],
            "load": function(query, callback) {
                fetch(`{{ route("api.suppliers.index") }}?owner={{ auth()->user()->parentCompany->parent_company_id }}&keyword=${encodeURIComponent(query)}`)
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

        const handleCalculatingDate = async () => {
            const timeValue = $(`#time`).val();
            const paymentTerm = $(`#payment-term`);
            const dueTime = $(`#due-time`);
            const payemntTermValue = paymentTerm[0].tomselect.options[paymentTerm.val()];
            var thisTime = new Date(timeValue);

            if (payemntTermValue) {
                if (payemntTermValue.deadline_type) {
                    dueTime.prop('disabled', true);

                    if (payemntTermValue.deadline_type == 'Day') {
                        thisTime.setDate(thisTime.getDate() + payemntTermValue.value);
                    } else if (payemntTermValue.deadline_type == 'Month') {
                        thisTime.setMonth(thisTime.getMonth() + payemntTermValue.value);
                    } else if (payemntTermValue.deadline_type == 'Year') {
                        thisTime.setYear(thisTime.getYear() + payemntTermValue.value);
                    } else {
                        dueTime.prop('disabled', false);
                    }
                } else {
                    dueTime.prop('disabled', false);
                }
            } else {
                dueTime.prop('disabled', true);
            }

            dueTime.val(thisTime.toLocaleDateString("en-US", {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }));
        }

        $(document).on('change', '#time', async function (e) {
            handleCalculatingDate();
        });

        $(document).on('change', '#payment-term', async function (e) {
            handleCalculatingDate();
        });

        $(document).on('change', '#supplier', async function (e) {
            $(`select[name="supplier_address"]`).prop(`disabled`, false);
            const supplier = $(`#supplier`);
            const supplierAddresses = supplier[0].tomselect.options[supplier.val()].billing_addresses;
            const supplierAddress = $(`#supplier-address`)[0].tomselect;

            if (!supplierAddress) {
                HSCore.components.HSTomSelect.init(`select[name="supplier_address"]`, {
                    "valueField": 'id',
                    "labelField": 'full_address',
                    "searchField": ['full_address'],
                    "options": supplierAddresses,
                    "render": {
                        option: function(data, escape) {
                            return `<div class="row">
                                <div class="col-sm-12">
                                    <div class="h4 w-100 mb-1">${escape(data.name)}</div>
                                        <div>${escape(data.phone)}</div>
                                        <div class="text-truncate">${escape(data.full_address)}</div>
                                    </div>
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
                                </div>
                            </div>`;
                        }
                    }
                });
            } else {
                supplierAddress.clear();
                supplierAddress.clearOptions();
                supplierAddress.addOptions(supplierAddresses);
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
                            history.back() ?? window.location.replace(`{{ route('billings.index') }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-create', async function (e) {
            const thisButton    = $(this);
            const url           = `{{ route('billings.index') }}`

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
                                            index: {
                                                text: 'Back',
                                                btnClass: 'btn-secondary',
                                                action: function () {
                                                    window.location.replace(`{{ route('billings.index') }}`);
                                                }
                                            },
                                            reCreate: {
                                                text: 'Recreate',
                                                btnClass: 'btn-primary',
                                                action: function () {
                                                    window.location.reload();
                                                }
                                            },
                                            edit: {
                                                text: 'Edit',
                                                btnClass: 'btn-success',
                                                action: function () {
                                                    window.location.replace(`{{ route('billings.index') }}/${res.data.id}/edit`);
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
    })();
</script>
@endsection