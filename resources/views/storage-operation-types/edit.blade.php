@extends('layouts.app')
@section('title', 'Edit ' . $query->name)

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('storage-operation-types.index') }}">Storage Operation Types</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('storage-operation-types.show', $query->id) }}">{{ $query->name }}</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('storage-operation-types.edit', $query->id) }}">Edit</a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title">Storage operation type information</h4>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-sm-12">
                        <label class="row form-check form-switch" for="is-enable">
                            <span class="col-8 col-sm-9 ms-0">
                                <span class="text-dark">
                                    Availability
                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Storage operation type availability switch toggler."></i>
                                </span>
                            </span>
                            <span class="col-4 col-sm-3 text-end">
                                <input id="is-enable" name="is_enable" type="checkbox" class="form-check-input" {{ $query->is_enable ? 'checked=""' : '' }}>
                            </span>
                        </label>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label for="storage" class="form-label">Storage</label>

                        <input id="storage" type="text" class="form-control" placeholder="" value="{{ $query->storage->name }}" autocomplete="off" disabled="">
                    </div>

                    <div class="col-sm-6 mb-4">
                        <label for="operation-type" class="form-label">Operation Type</label>

                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend input-group-text p-0">
                                <span class="btn btn-soft-{{ $query->operationType->group == 'In' ? 'success text-success' : ($query->operationType->group == 'Out' ? 'danger text-danger' : 'secondary text-muted') }}">
                                    {{ $query->operationType->group }} 
                                </span>
                            </div>

                            <input id="operation-type" type="text" class="form-control" placeholder="" aria-label="" value="{{ $query->operationType->name }}" autocomplete="off" disabled="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label for="name" class="form-label">Name</label>

                        <input id="name" name="name" type="text" class="form-control" placeholder="" value="{{ $query->name }}" autocomplete="off">
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
                        <button type="button" class="btn btn-primary btn-save">Save</button>
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
<!-- JS Dropzone -->
<script src="{{ asset('assets/vendor/dropzone/dist/min/dropzone.min.js') }}"></script>

<script>
    (function () {
        HSCore.components.HSTomSelect.init('.js-select');

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
                            history.back() ?? window.location.replace(`{{ route('storage-operation-types.index') }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-save', async function (e) {
            const thisButton    = $(this);
            const listNote      = '';
            const url           = `{{ route('storage-operation-types.show', $query->id) }}`;

            await $.confirm({
                title: 'Confirmation!',
                content: `Do you want to save this form?${listNote ?? ''}`,
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
                                                text: 'Back',
                                                btnClass: 'btn-secondary',
                                                action: function () {
                                                    window.location.replace(`{{ route('storage-operation-types.index') }}`)
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
    })();
</script>
@endsection