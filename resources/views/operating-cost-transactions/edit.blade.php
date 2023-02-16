@extends('layouts.app')
@section('title', 'Edit ' . date('F j, Y', strtotime($query->transaction_time)))

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('operating-cost-transactions.index') }}">Operating Cost Transactions</a>
</li>
<li class="list-inline-item text-end">
    <a class="list-separator-link" href="{{ route('operating-cost-transactions.show', $query->id) }}">{{ date('F j, Y', strtotime($query->transaction_time)) }}</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('operating-cost-transactions.edit', $query->id) }}">Edit</a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="float-start card-header-title">Operating cost transaction information</h4>

                <span class="float-end badge {{ $query->status->background_color . ' ' . $query->status->font_color }}" style="min-width: 100px;">
                    <span class="legend-indicator {{ str_replace('soft-', '', $query->status->background_color) }}"></span>
                    {{ $query->status->name }}
                </span>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-4">
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

                    <div class="col-sm-6 mb-4">
                        <label for="internal-code" class="form-label">Internal Code</label>

                        <input id="internal-code" name="internal_code" type="text" class="form-control" placeholder="" value="{{ $query->internal_code }}" autocomplete="off">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label for="note" class="form-label">Note</label>

                        <textarea id="note" name="note" class="form-control textarea">{{ $query->note }}</textarea>
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
        HSCore.components.HSTomSelect.init('.js-select');
        HSCore.components.HSFlatpickr.init('.js-flatpickr');

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
                            history.back() ?? window.location.replace(`{{ route('operating-cost-transactions.show', $query->id) }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-save', async function (e) {
            const thisButton    = $(this);
            const url           = `{{ route('operating-cost-transactions.show', $query->id) }}`

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
                            values['_method']   = `PUT`;
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
                                                text: 'Show',
                                                btnClass: 'btn-primary',
                                                action: function () {
                                                    window.location.replace(`{{ route('operating-cost-transactions.show', $query->id) }}`)
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
            const url = `{{ route('operating-costs.show', $query->id) }}`
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
    })();
</script>
@endsection