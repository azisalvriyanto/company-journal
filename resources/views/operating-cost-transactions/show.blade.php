@extends('layouts.app')
@section('title', 'Show ' . date('F j, Y', strtotime($query->transaction_time)))

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('operating-cost-transactions.index') }}">Operating Cost Transactions</a>
</li>
<li class="list-inline-item text-end">
    <a class="list-separator-link" href="{{ route('operating-cost-transactions.show', $query->id) }}">{{ date('F j, Y', strtotime($query->transaction_time)) }}</a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title">Operating cost transaction information</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3 mb-4">
                        <label for="time" class="form-label">Time</label>

                        <div>{{ date('F j, Y', strtotime($query->transaction_time)) }}</div>
                    </div>

                    <div class="col-sm-3 mb-4">
                        <label for="internal-code" class="form-label">Code</label>

                        <div>{!! $query->code ?? '<span class="text-muted fst-italic">Empty</span>' !!}</div>
                    </div>

                    <div class="col-sm-3 mb-4">
                        <label for="internal-code" class="form-label">Internal Code</label>

                        <div>{!! $query->internal_code ?? '<span class="text-muted fst-italic">Empty</span>' !!}</div>
                    </div>

                    <div class="col-sm-3 mb-4">
                        <label for="internal-code" class="form-label">Status</label>

                        <div>
                            <span class="badge {{ $query->status->background_color . ' ' . $query->status->font_color }}" style="width: 100px;">{{ $query->status->name }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label for="note" class="form-label">Note</label>

                        <div>{!! $query->note ?? '<span class="text-muted">Empty</span>' !!}</div>
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
                <h4 class="card-header-title float-start">Operating cost</h4>

                @if ($query->status->name == 'Draft')
                <button class="btn-details-create btn btn-sm btn-soft-success float-end">
                    <i class="bi bi-file-earmark-plus"></i>
                    Create
                </button>
                @endif
            </div>

            <div class="card-body p-0">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="datatable-custom table-responsive">
                            <table id="datatableOperatingCost"
                                class="js-datatable table table-sm table-bordered table-hover table-thead-bordered table-nowrap table-align-middle card-table w-100"
                                data-hs-datatables-options='{
                                    "orderCellsTop": true,
                                    "isResponsive": true,
                                    "isShowPaging": false,
                                    "deferRender": true,
                                    "processing": true,
                                    "serverSide": true,
                                    "ajax": "{{ route("operating-cost-transactions.details.index", ["operating_cost_transaction" => $query->id]) }}",
                                    "columns": [
                                        {
                                            "data": "actions",
                                            "name": "actions",
                                            "orderable": false,
                                            "searchable": false,
                                            "className": "text-center"
                                        },
                                        {
                                            "data": "operating_cost.name",
                                            "name": "operatingCost.name"
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
                                        <th rowspan="1" colspan="1"></th>
                                        <th rowspan="1" colspan="1">Total</th>
                                        <th rowspan="1" colspan="3">
                                            <span name="operating_cost_transaction[total_price]" class="h1">{{ number_format($query->total_price, 0, '.', ',') }}</span>
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
                        <button class="btn-details-create btn btn-sm btn-soft-success float-end">
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
                        <a class="btn btn-ghost-light btn-discard" href="{{ route('operating-cost-transactions.index') }}">Discard</a>
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
        const datatableOperatingCost = HSCore.components.HSDatatables.getItem('datatableOperatingCost');

        datatableOperatingCost.on('draw.dt', async function (e, settings, json, xhr) {
            const listTransaction = $("#datatableOperatingCost tbody").children();
            await listTransaction.each(async function(index, transaction) {
                const thisRow = $(transaction);
                const thisId = thisRow.attr('data-id');

                handleGenerateOperatingCost(thisId);
            });
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

        const handleGenerateOperatingCost = async (thisId) => {
            HSCore.components.HSTomSelect.init(`select[name="operating_cost_transaction_details[${thisId}][operating_cost]"]`, {
                "valueField": 'id',
                "labelField": 'name',
                "searchField": ['name'],
                "load": function(query, callback) {
                    fetch(`{{ route("api.operating-costs.index") }}?owner={{ auth()->user()->parentCompany->parent_company_id }}&keyword=${encodeURIComponent(query)}`)
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
        }

        const handleCalculating = async () => {
            const listTransaction = $("#datatableOperatingCost tbody").children();

            var totalTotalPrice = 0;
            await listTransaction.each(async function(index, transaction) {
                const thisRow = $(transaction);
                const thisId = thisRow.attr('data-id');

                $(transaction).attr('data-quantity',    $(`[name="operating_cost_transaction_details[${thisId}][quantity]"]`).val()    ?? $(transaction).attr('data-quantity'));
                $(transaction).attr('data-price',       $(`[name="operating_cost_transaction_details[${thisId}][price]"]`).val()       ?? $(transaction).attr('data-price'));

                const quantity = $(transaction).attr('data-quantity');
                const price = $(transaction).attr('data-price');
                const totalPrice = quantity*price;

                $(transaction).attr('data-total-price', totalPrice)
                $(`[name="operating_cost_transaction_details[${thisId}][total_price]"]`).html(handleNumberFormat(totalPrice, 0));

                totalTotalPrice += totalPrice;
                $(`[name="operating_cost_transaction[total_price]"]`).html(handleNumberFormat(totalTotalPrice, 0));
            });
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
                            history.back() ?? window.location.replace(`{{ route('operating-cost-transactions.index') }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.datatable-btn-status', async function (e) {
            const thisButton    = $(this);
            const thisTr        = thisButton.parentsUntil('tr').parent();
            const url           = `{{ route('operating-cost-transactions.show', $query->id) }}/status`;

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
                            history.back() ?? window.location.replace(`{{ route('operating-cost-transactions.edit', $query->id) }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-save', async function (e) {
            const thisButton    = $(this);
            const url           = `{{ route('operating-cost-transactions.details.index', ['operating_cost_transaction' => $query->id]) }}`;

            await $.confirm({
                title: 'Confirmation!',
                content: `Do you want to save this form?`,
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
                        text: 'Yes, Save',
                        btnClass: 'btn-primary',
                        action: async function () {
                            var values          = [];
                            $(`[name]`).map(function() {
                                const parameter = $(this).attr('name');

                                let value = '';
                                if ($(this).attr('type') == 'checkbox') {
                                    value = $(this).is(":checked") ? 1 : 0;
                                } else {
                                    value = $(this).val();
                                }
                                values[parameter] = value;
                            });
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
                                            index: {
                                                text: 'Back',
                                                btnClass: 'btn-primary',
                                                action: function () {
                                                    window.location.replace(`{{ route('operating-cost-transactions.show', $query->id) }}`);
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
            const url = `{{ route('operating-cost-transactions.show', $query->id) }}`
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
                                                    window.location.replace(`{{ route('operating-cost-transactions.index') }}`);
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

        $(document).on('click', '.btn-details-create', async function (e) {
            const listTransaction = $("#datatableOperatingCost tbody");
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
                        <button type="button" class="btn-details-remove btn btn-xs btn-danger">
                            <i class="bi bi-trash3 me-1"></i>
                            Remove
                        </button>
                    </td>
                    <td>
                        <div class="tom-select-custom">
                            <select name="operating_cost_transaction_details[${thisId}][operating_cost]" class="form-select" autocomplete="off"
                                data-hs-tom-select-options='{
                                    "searchInDropdown": true,
                                    "hideSearch": false,
                                    "placeholder": "Search..."
                            }'></select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input id="price" name="operating_cost_transaction_details[${thisId}][quantity]" type="text" class="input-count form-control text-end" placeholder="" value="" autocomplete="off">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input id="price" name="operating_cost_transaction_details[${thisId}][price]" type="text" class="input-count form-control text-end" placeholder="" value="" autocomplete="off">
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="form-group">
                            <label name="operating_cost_transaction_details[${thisId}][total_price]">0</label>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="form-group">
                            <textarea class="form-control" name="operating_cost_transaction_details[${thisId}][note]" rows="1"></textarea>
                        </div>
                    </td>
                </tr>
            `);

            handleGenerateOperatingCost(thisId);
        });

        $(document).on('click', '.btn-details-remove', async function (e) {
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
        });

        $(document).on('input', '.input-count', async function (e) {
            handleCalculating();
        });
    })();
</script>
@endsection