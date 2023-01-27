@extends('layouts.app')
@section('title', 'Create Operating Cost')

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('operating-costs.index') }}">Operating Costs</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('operating-costs.create') }}">Create Operating Cost</a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title">Operating cost information</h4>
            </div>

            <div class="card-body">

                <div class="mb-4">
                    <label class="row form-check form-switch" for="is-enable">
                        <span class="col-8 col-sm-9 ms-0">
                            <span class="text-dark">
                                Availability
                                <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Operating cost availability switch toggler."></i>
                            </span>
                        </span>
                        <span class="col-4 col-sm-3 text-end">
                            <input id="is-enable" name="is_enable" type="checkbox" class="form-check-input" checked="">
                        </span>
                    </label>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <label for="name" class="form-label">Name</label>

                    <input id="name" name="name" type="text" class="form-control" placeholder="Home Electricity, Labor, etc." value="" autocomplete="off">
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-4">
                            <label for="default-cost" class="form-label">Default Cost</label>

                            <input id="default-cost" name="default_cost" type="text" class="form-control" placeholder="x,xx.xx" value="">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="mb-4">
                            <label for="unit-of-measurement" class="form-label">Unit of Measurement</label>

                            <div class="tom-select-custom">
                                <select id="unit-of-measurement" name="unit_of_measurement"
                                    class="js-select form-select" autocomplete="off" data-hs-tom-select-options='{
                                        "searchInDropdown": true,
                                        "hideSearch": false,
                                        "placeholder": "Search..."
                                }'>
                                    <option value="">Search...</option>
                                    @foreach($unitOfMeasurements as $unitOfMeasurement)
                                    <option value="{{ $unitOfMeasurement['id'] }}">
                                        {{ $unitOfMeasurement['name'] }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
                            history.back() ?? window.location.replace(`{{ route('operating-costs.index') }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-create', async function (e) {
            const thisButton    = $(this);
            const url           = `{{ route('operating-costs.index') }}`

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
                            values['owner']     = `{{ auth()->user()->parentCompany->parent_company_id }}`;
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
                                                    window.location.replace(`{{ route('operating-costs.index') }}`);
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
                                                    window.location.replace(`{{ route('operating-costs.index') }}/${res.data.id}/edit`);
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