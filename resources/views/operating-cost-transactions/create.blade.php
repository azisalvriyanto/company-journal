@extends('layouts.app')
@section('title', 'Create Operating Cost Transaction')

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('operating-cost-transactions.index') }}">Operating Cost Transactions</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('operating-cost-transactions.create') }}">Create Operating Cost Transaction</a>
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

                            <input type="text" id="time" name="time" class="flatpickr-custom-form-control form-control" placeholder="Select dates" data-input value="{{ date('F j, Y') }}">
                        </div>
                    </div>

                    <div class="col-sm-6 mb-4">
                        <label for="internal-code" class="form-label">Internal Code</label>

                        <input id="internal-code" name="internal_code" type="text" class="form-control" placeholder="" value="" autocomplete="off">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label for="note" class="form-label">Note</label>

                        <textarea id="note" name="note" class="form-control textarea"></textarea>
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

        $(document).on('click', '.btn-create', async function (e) {
            const thisButton    = $(this);
            const url           = `{{ route('operating-cost-transactions.index') }}`

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
                                        content: `${res.message ?? ''}`,
                                        type: 'orange',
                                        buttons: {
                                            index: {
                                                text: 'Back',
                                                btnClass: 'btn-secondary',
                                                action: function () {
                                                    window.location.replace(`{{ route('operating-cost-transactions.index') }}`);
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
                                                    window.location.replace(`{{ route('operating-cost-transactions.index') }}/${res.data.id}/edit`);
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