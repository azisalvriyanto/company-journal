@extends('layouts.app')
@section('title', 'Create Item')

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('items.items.index') }}">Items</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('items.items.create') }}">Create Item</a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title">Item information</h4>
            </div>

            <div class="card-body">

                <div class="mb-4">
                    <label class="row form-check form-switch" for="is-enable">
                        <span class="col-8 col-sm-9 ms-0">
                            <span class="text-dark">
                                Availability
                                <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Item availability switch toggler."></i>
                            </span>
                        </span>
                        <span class="col-4 col-sm-3 text-end">
                            <input id="is-enable" name="is_enable" type="checkbox" class="form-check-input" checked="">
                        </span>
                    </label>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <label for="name" class="form-label">
                        Name
                        <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Items are the goods or services you sell."></i>
                    </label>

                    <input id="name" name="name" type="text" class="form-control"
                        placeholder="Shirt, t-shirts, etc." aria-label="Shirt, t-shirts, etc." value="">
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-4">
                            <label for="product-code" class="form-label">Product Code</label>

                            <input id="product-code" name="product_code" type="text" class="form-control"
                                placeholder="eg. 348121032" aria-label="eg. 348121032">
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

                            <small class="form-text">Used to calculate shipping rates at checkout and label prices
                                during fulfillment.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3 mb-lg-5">
            <div class="card-header card-header-content-between">
                <h4 class="card-header-title">Media</h4>
            </div>

            <div class="card-body">
                <div id="image-url" name="image_url" class="js-dropzone dz-dropzone dz-dropzone-card">
                    <div class="dz-message">
                        <img class="avatar avatar-xl avatar-4x3 mb-3"
                            src="{{ asset('assets/svg/illustrations/oc-browse.svg') }}" alt="Browse File"
                            data-hs-theme-appearance="default">
                        <img class="avatar avatar-xl avatar-4x3 mb-3"
                            src="{{ asset('assets/svg/illustrations-light/oc-browse.svg') }}" alt="Browse File"
                            data-hs-theme-appearance="dark">

                        <h5>Drag and drop your file here</h5>

                        <p class="mb-2">or</p>

                        <span class="btn btn-white btn-sm">Browse files</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-header-title">Organization</h4>
            </div>

            <div class="card-body">
                <div class="mb-4">
                    <label for="categpry" class="form-label">Category</label>

                    <div class="tom-select-custom">
                        <select class="js-select form-select" autocomplete="off" id="categpry"
                            data-hs-tom-select-options='{
                                "searchInDropdown": true,
                                "hideSearch": false,
                                "placeholder": "Search..."
                        }'>
                            <option value="">Search...</option>
                            @foreach($categories as $category)
                            <option value="{{ $category['id'] }}">
                                {{ $category['name'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="detail-group" class="form-label">Detail Group</label>

                    <div class="tom-select-custom">
                        <select id="detail-group" name="detail_group" class="js-select form-select" autocomplete="off"
                            data-hs-tom-select-options='{
                                "searchInDropdown": true,
                                "hideSearch": false,
                                "placeholder": "Search..."
                        }'>
                            <option value="">Search...</option>
                            @foreach($detailGroups as $detailGroup)
                            <option value="{{ $detailGroup['id'] }}">
                                {{ $detailGroup['name'] }}
                            </option>
                            @endforeach
                        </select>
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
        // HSCore.components.HSTomSelect.init(`select[name="detail_group"]`, {
		//     "valueField": 'id',
		//     "labelField": 'name',
        //     "searchField": ['name'],
		//     "options": [],
        //     "load": function(query, callback) {
        //         fetch(`{{ route("api.items.detail-groups.index") }}?keyword=${encodeURIComponent(query)}`)
        //         .then(response => response.json())
        //         .then(json => {
        //             callback(json.data);
        //         })
        //         .catch(e => {
        //             callback();
        //         });
        //     },
        //     "render": {
        //         option: function(data, escape) {
        //             return `<div>${escape(data.name)}</div>`;
        //         },
        //         item: function(data, escape) {
        //             return `<div>${escape(data.name)}</div>`;
        //         }
        //     }
        // });

        HSCore.components.HSTomSelect.init('.js-select');
        HSCore.components.HSDropzone.init('.js-dropzone', {
            maxFiles: 1,
            uploadMultiple: false,
            acceptedFiles: ".jpeg,.jpg,.png",
            init: function() {
                this.on("maxfilesexceeded", function(file) {
                    this.removeFile(file);
                });
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
                        btnClass: 'btn-danger',
                        action: async function () {
                            history.back() ?? window.location.replace(`{{ route('items.items.index') }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-create', async function (e) {
            const thisButton    = $(this);
            const url           = `{{ route('items.items.index') }}`

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
                                        type: 'orange',
                                        buttons: {
                                            index: {
                                                text: 'Back',
                                                btnClass: 'btn-secondary',
                                                action: function () {
                                                    window.location.replace(`{{ route('items.unit-of-measurements.index') }}`);
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
                                                    window.location.replace(`{{ route('items.unit-of-measurements.index') }}/${res.data.id}/edit`);
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