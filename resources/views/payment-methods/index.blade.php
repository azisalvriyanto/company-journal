@extends('layouts.app')
@section('title', 'Payment Methods')

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('payment-methods.index') }}">Payment Methods</a>
</li>
@endsection

@section('content')
<div class="card mb-3 mb-lg-5">
    <div class="card-header">
        <div class="row justify-content-between align-items-center flex-grow-1">
            <div class="col-md">
                <h4 class="card-header-title">Payment Methods</h4>
            </div>

            <div class="col-auto">
                <div class="dropdown me-2">
                    <a class="btn btn-primary btn-sm" href="{{ route('payment-methods.create') }}">
                        <i class="bi-clipboard-plus-fill me-2"></i> Create
                    </a>

                    <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="datatablePaymentMethodExportDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi-download me-2"></i> Export
                    </button>

                    <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="datatablePaymentMethodExportDropdown"
                        style="">
                        <span class="dropdown-header">Options</span>
                        <a class="dropdown-item datatable-export" data-id="copy" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4x3 me-2"
                                src="{{ asset('assets/svg/illustrations/copy-icon.svg') }}" alt="Image Description">
                            Copy
                        </a>
                        <a class="dropdown-item datatable-export" data-id="print" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4x3 me-2"
                                src="{{ asset('assets/svg/illustrations/print-icon.svg') }}" alt="Image Description">
                            Print
                        </a>
                        <div class="dropdown-divider"></div>
                        <span class="dropdown-header">Download options</span>
                        <a class="dropdown-item datatable-export" data-id="excel" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4x3 me-2"
                                src="{{ asset('assets/svg/brands/excel-icon.svg') }}" alt="Image Description">
                            Excel
                        </a>
                        <a class="dropdown-item datatable-export" data-id="csv" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4x3 me-2"
                                src="{{ asset('assets/svg/components/placeholder-csv-format.svg') }}"
                                alt="Image Description">
                            .CSV
                        </a>
                        <a class="dropdown-item datatable-export" data-id="pdf" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4x3 me-2"
                                src="{{ asset('assets/svg/brands/pdf-icon.svg') }}" alt="Image Description">
                            PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="datatable-custom">
        <table id="datatablePaymentMethod"
            class="js-datatable table table-sm table-bordered table-hover table-thead-bordered table-nowrap table-align-middle card-table w-100"
            data-hs-datatables-options='{
                "orderCellsTop": true,
                "isResponsive": false,
                "isShowPaging": false,
                "entries": "#datatableEntries",
                "deferRender": true,
                "info": {
                    "totalQty": "#datatablePaymentMethodWithPaginationInfoTotalQty"
                },
                "pagination": "datatablePaymentMethodWithPagination",
                "dom": "Bfrtip",
                "buttons": [
                    {
                        "extend": "copy",
                        "className": "d-none"
                    },
                    {
                        "extend": "excel",
                        "className": "d-none"
                    },
                    {
                        "extend": "csv",
                        "className": "d-none"
                    },
                    {
                        "extend": "pdf",
                        "className": "d-none"
                    },
                    {
                        "extend": "print",
                        "className": "d-none"
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ request()->url() }}",
                "columns": [
                    {
                        "data": "DT_RowIndex",
                        "name": "id",
                        "orderable": false,
                        "searchable": false,
                        "className": "text-center"
                    },
                    {
                        "data": "name",
                        "name": "name"
                    },
                    {
                        "data": "is_enable",
                        "name": "is_enable",
                        "className": "text-center"
                    },
                    {
                        "data": "actions",
                        "name": "actions",
                        "orderable": false,
                        "searchable": false,
                        "className": "text-center"
                    }
                ],
                "order": [
                    [1, "asc"]
                ]
            }'>
            <thead class="thead-light">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="1">Name</th>
                    <th rowspan="1">Status</th>
                    <th rowspan="2">Actions</th>
                </tr>
                <tr>
                    <th>
                        <input type="text" class="form-control form-control-sm datatable-search"
                            placeholder="Search..." data-id="1">
                    </th>
                    <th>
                        <div class="tom-select-custom">
                            <select class="js-select js-datatable-filter form-select form-select-sm form-select-borderless p-0" autocomplete="off" data-target-column-index="2" data-target-table="datatablePaymentMethod" data-hs-tom-select-options='{
                                "searchInDropdown": false,
                                "hideSearch": true
                            }'>
                                <option value="null" selected="">Any</option>
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                    </th>
                </tr>
            </thead>    
        </table>
    </div>

    <div class="card-footer">
        <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
            <div class="col-sm mb-2 mb-sm-0">
                <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                    <span class="me-2">Showing:</span>

                    <div class="tom-select-custom">
                        <select id="datatableEntries" class="js-select form-select form-select-borderless" style="width: 100px;"
                            autocomplete="off" data-hs-tom-select-options='{
                            "searchInDropdown": false,
                            "hideSearch": true
                        }'>
                            <option value="10" selected="">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="150">150</option>
                        </select>
                    </div>

                    <span class="text-secondary me-2">of</span>

                    <span id="datatablePaymentMethodWithPaginationInfoTotalQty"></span>
                </div>
            </div>

            <div class="col-sm-auto">
                <div class="d-flex justify-content-center justify-content-sm-end">
                    <nav id="datatablePaymentMethodWithPagination" aria-label="Activity pagination"></nav>
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
<script src="{{ asset('assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jszip/dist/jszip.min.js') }}"></script>
<script src="{{ asset('assets/vendor/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/vendor/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- JS Select -->
<script src="{{ asset('assets/vendor/datatables.net.extensions/select/select.min.js') }}"></script>

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
        const datatablePaymentMethod = HSCore.components.HSDatatables.getItem('datatablePaymentMethod');

        $(document).on('keyup', `.datatable-search`, function(e) {
            const datatable = $(this).parentsUntil('table').parent().attr('id');
            const columnId  = $(this).data('id');

            eval(datatable)
                .columns(columnId)
                .search(this.value)
                .draw();
        });

        $(document).on('click', `.datatable-export`, function(e) {
            const datatable = $(this).parentsUntil('.card').next().find('.js-datatable').attr('id');
            const functionId  = $(this).data('id');

            eval(datatable).button(`.buttons-${functionId}`).trigger();
        });

        document.querySelectorAll('.js-datatable-filter').forEach(function (item) {
            item.addEventListener('change', function(e) {
                const elVal = e.target.value,
                    targetColumnIndex = e.target.getAttribute('data-target-column-index'),
                    targetTable = e.target.getAttribute('data-target-table');

                HSCore.components.HSDatatables.getItem(targetTable).column(targetColumnIndex).search(elVal !== 'null' ? elVal : '').draw()
            })
        });

        $(document).on('click', '.datatable-btn-destroy', async function (e) {
            const thisButton    = $(this);
            const thisTr        = thisButton.parentsUntil('tr').parent();
            const url           = thisTr.data('url');

            const listNote      = `
            <table class="table table-sm table-borderless">
                <thead>
                    <tr>
                        <td style="width: 20%;"></td>
                        <td style="width: 1px;"></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-0">Name</td>
                        <td class="p-0 text-center">:</td>
                        <td class="p-0">${thisTr.data('name')}</td>
                    </tr>
                </tbody>
            </table>
            `;

            await $.confirm({
                title: 'Confirmation!',
                content: `Do you want to delete this list?${listNote ?? ''}`,
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
                                    datatablePaymentMethod.ajax.reload(null, false);

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
    })();
</script>
@endsection