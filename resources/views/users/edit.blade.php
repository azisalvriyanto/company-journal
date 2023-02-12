@extends('layouts.app')
@section('title', 'Edit ' . $query->name)

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('users.index') }}">Users</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('users.show', $query->id) }}">{{ $query->name }}</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('users.edit', $query->id) }}">Edit</a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title">User information</h4>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-sm-12">
                        <label class="row form-check form-switch" for="is-enable">
                            <span class="col-8 col-sm-9 ms-0">
                                <span class="text-dark">
                                    Availability
                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="User availability switch toggler."></i>
                                </span>
                            </span>
                            <span class="col-4 col-sm-3 text-end">
                                <input id="is-enable" name="is_enable" type="checkbox" class="form-check-input" checked="">
                            </span>
                        </label>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label for="group" class="form-label">Group</label>

                        <div class="tom-select-custom">
                            <select id="group" name="group" class="js-select form-select" autocomplete="off"
                                data-hs-tom-select-options='{
                                    "hideSearch": true,
                                    "placeholder": "Select..."
                            }'>
                                @foreach($groups as $group)
                                <option value="{{ $group['id'] }}" <?= ($group['id'] == $query->group ? 'selected' : '') ?>>
                                    {{ $group['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 mb-4">
                        <label for="parent-company" class="form-label">Parent Company</label>

                        <div class="tom-select-custom">
                            <select id="parent-company" name="parent_company" class="form-select" autocomplete="off"
                                data-hs-tom-select-options='{
                                    "searchInDropdown": true,
                                    "hideSearch": false,
                                    "placeholder": "Search..."
                            }'>
                                <option
                                    selected=""
                                    value="{{ $query->parent_company_id }}"
                                    data-name="{{ $query->parentCompany->name }}"
                                ></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label for="name" class="form-label">Name</label>

                        <input id="name" name="name" type="text" class="form-control" placeholder="" value="{{ $query->name }}" autocomplete="off">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label for="code" class="form-label">Code</label>

                        <input id="code" name="code" type="text" class="form-control" placeholder="" value="{{ $query->code }}" autocomplete="off">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label for="email" class="form-label">Email</label>

                        <input id="email" name="email" type="email" class="form-control" placeholder="" value="{{ $query->email }}" autocomplete="off">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label for="owner-types" class="form-label">Owner Groups</label>

                        <div class="tom-select-custom tom-select-custom-with-tags">
                            <select id="owner-types" name="owner_types[]" multiple class="form-select" autocomplete="off"
                                data-hs-tom-select-options='{
                                    "searchInDropdown": false,
                                    "hideSearch": true,
                                    "hideSelected": true,
                                    "placeholder": "Search...",
                        	        "maxItems": 3,
                                    "allowEmptyOption": true
                            }'>
                                @foreach($ownerTypes as $ownerType)
                                <option value="{{ $ownerType['id'] }}" <?= (in_array($ownerType['id'], $query->ownerTypes->pluck('id')->toArray()) ? 'selected=""' : '') ?>>
                                    {{ $ownerType['name'] }}
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

<div class="row" data-id="contact_address">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title float-start">Contact Address</h4>

                <button class="btn-address-create btn btn-sm btn-soft-success float-end">
                    <i class="bi bi-file-earmark-plus"></i>
                    Create
                </button>
            </div>

            <div class="card-body">
                @foreach($query->contacts->where('group', 'Contact')->all() as $contactAddress)
                <div data-id="{{ $contactAddress->id }}" class="address mb-5" style="border-right: 10px solid <?= ($query->default_contact_address_id == $contactAddress->id ? '#377dff' : 'transparent')?> ;">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div name="contact_address[{{ $contactAddress->id }}][name]" class="h3 mb-1">{{ $contactAddress->name }}</div>
                            <div name="contact_address[{{ $contactAddress->id }}][phone]">{{ $contactAddress->phone }}</div>
                            <div name="contact_address[{{ $contactAddress->id }}][full_address]" class="text-truncate">{{ $contactAddress->full_address }}</div>
                                <div name="contact_address[{{ $contactAddress->id }}][is_default]" hidden="">{{ $query->default_contact_address_id == $contactAddress->id ? 'true' : 'false' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="list-inline list-separator">
                                <li class="list-inline-item">
                                    <a href="javascript:;" class="btn-address-edit list-separator-link text-warning">Edit</a>
                                </li>
                                @if($query->default_contact_address_id != $contactAddress->id)
                                <li class="list-inline-item">
                                    <a href="javascript:;" class="btn-address-set-default list-separator-link text-success">Make Primary Address</a>
                                </li>
                                @endif
                                <li class="list-inline-item">
                                    <a href="javascript:;" class="btn-address-remove list-separator-link text-danger">Remove</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row" data-id="billing_address">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title float-start">Billing Address</h4>

                <button class="btn-address-create btn btn-sm btn-soft-success float-end">
                    <i class="bi bi-file-earmark-plus"></i>
                    Create
                </button>
            </div>

            <div class="card-body">
                @foreach($query->contacts->where('group', 'Billing')->all() as $contactAddress)
                <div data-id="{{ $contactAddress->id }}" class="address mb-5" style="border-right: 10px solid <?= ($query->default_billing_address_id == $contactAddress->id ? '#377dff' : 'transparent')?> ;">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div name="billing_address[{{ $contactAddress->id }}][name]" class="h3 mb-1">{{ $contactAddress->name }}</div>
                            <div name="billing_address[{{ $contactAddress->id }}][phone]">{{ $contactAddress->phone }}</div>
                            <div name="billing_address[{{ $contactAddress->id }}][full_address]" class="text-truncate">{{ $contactAddress->full_address }}</div>
                                <div name="billing_address[{{ $contactAddress->id }}][is_default]" hidden="">{{ $query->default_billing_address_id == $contactAddress->id ? 'true' : 'false' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="list-inline list-separator">
                                <li class="list-inline-item">
                                    <a href="javascript:;" class="btn-address-edit list-separator-link text-warning">Edit</a>
                                </li>
                                @if($query->default_billing_address_id != $contactAddress->id)
                                <li class="list-inline-item">
                                    <a href="javascript:;" class="btn-address-set-default list-separator-link text-success">Make Primary Address</a>
                                </li>
                                @endif
                                <li class="list-inline-item">
                                    <a href="javascript:;" class="btn-address-remove list-separator-link text-danger">Remove</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row" data-id="shipping_address">
    <div class="col-lg-12 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title float-start">Shipping Address</h4>

                <button class="btn-address-create btn btn-sm btn-soft-success float-end">
                    <i class="bi bi-file-earmark-plus"></i>
                    Create
                </button>
            </div>

            <div class="card-body">
                @foreach($query->contacts->where('group', 'Shipping')->all() as $contactAddress)
                <div data-id="{{ $contactAddress->id }}" class="address mb-5" style="border-right: 10px solid <?= ($query->default_shipping_address_id == $contactAddress->id ? '#377dff' : 'transparent')?> ;">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div name="shipping_address[{{ $contactAddress->id }}][name]" class="h3 mb-1">{{ $contactAddress->name }}</div>
                            <div name="shipping_address[{{ $contactAddress->id }}][phone]">{{ $contactAddress->phone }}</div>
                            <div name="shipping_address[{{ $contactAddress->id }}][full_address]" class="text-truncate">{{ $contactAddress->full_address }}</div>
                                <div name="shipping_address[{{ $contactAddress->id }}][is_default]" hidden="">{{ $query->default_shipping_address_id == $contactAddress->id ? 'true' : 'false' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="list-inline list-separator">
                                <li class="list-inline-item">
                                    <a href="javascript:;" class="btn-address-edit list-separator-link text-warning">Edit</a>
                                </li>
                                @if($query->default_shipping_address_id != $contactAddress->id)
                                <li class="list-inline-item">
                                    <a href="javascript:;" class="btn-address-set-default list-separator-link text-success">Make Primary Address</a>
                                </li>
                                @endif
                                <li class="list-inline-item">
                                    <a href="javascript:;" class="btn-address-remove list-separator-link text-danger">Remove</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
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
                        <button type="button" class="btn btn-primary btn-save">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div id="addressModal" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="addressModalTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-top-cover bg-dark text-center">
                <figure class="position-absolute end-0 bottom-0 start-0">
                    <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
                        <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"/>
                    </svg>
                </figure>

                <div class="modal-close">
                    <button type="button" class="btn-close btn-close-light" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <!-- End Header -->

            <div class="modal-top-cover-icon">
                <span class="icon icon-lg icon-light icon-circle icon-centered shadow-sm">
                    <i class="bi-receipt fs-2"></i>
                </span>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <label for="address-name" class="form-label">Name</label>

                        <input id="address-name" name="address_name" type="text" class="form-control" placeholder="" value="" autocomplete="off">
                    </div>

                    <div class="col-sm-6 mb-4">
                        <label for="address-phone" class="form-label">Phone</label>

                        <input id="address-phone" name="address_phone" type="text" class="form-control" placeholder="" value="" autocomplete="off">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <label for="address-full-address" class="form-label">Fill Address</label>

                        <textarea id="address-full-address" name="address_full_address" class="form-control textarea"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-bs-dismiss="modal">Close</button>
                <button id="btn-address-save" type="button" class="btn btn-primary" data-address-type="" data-id="" data-is-default="">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
@endsection

@section('javascript')
<!-- JS Dropzone -->
<script src="{{ asset('assets/vendor/hs-toggle-password/dist/js/hs-toggle-password.js') }}"></script>

<script>
    (function () {
        HSCore.components.HSTomSelect.init('.js-select');
        new HSTogglePassword('.js-toggle-password');

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
                            history.back() ?? window.location.replace(`{{ route('users.index') }}`);
                        }
                    },
                }
            });
        });

        $(document).on('click', '.btn-save', async function (e) {
            const thisButton    = $(this);
            const url           = `{{ route('users.show', $query->id) }}`

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
                                        type: 'orange',
                                        content: `${res.message ?? ''}`,
                                        autoClose: 'close|3000',
                                        buttons: {
                                            index: {
                                                text: 'Back',
                                                btnClass: 'btn-secondary',
                                                action: function () {
                                                    window.location.replace(`{{ route('users.index') }}`)
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
            const url = `{{ route('users.show', $query->id) }}`
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
                                                    window.location.replace(`{{ route('users.index') }}`);
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

        $(document).on('change', '#group', async function (e) {
            const thisElement  = $(this);
            const value = thisElement.val();
            const passwordRow = $(`#password`).closest('.row');
            if (value == 'Company' || value == 'Storage') {
                passwordRow.prop('hidden', true);
            } else {
                passwordRow.prop('hidden', false);
            }
        });

        HSCore.components.HSTomSelect.init(`select[name="parent_company"]`, {
		    "valueField": 'id',
		    "labelField": 'name',
            "searchField": ['name'],
            "load": function(query, callback) {
                fetch(`{{ route("api.companies.index") }}?keyword=${encodeURIComponent(query)}`)
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

        HSCore.components.HSTomSelect.init(`select[name="owner_types[]"]`);

        const handleSetContactDefault = (thisRow) => {
            const addresses = thisRow.closest('.card-body');
            const addressType = addresses.closest('.row').attr('data-id');
            addresses.find('.address').css({
                'border-right': '10px solid transparent'
            });
            addresses.find('.list-inline').html(`
                <li class="list-inline-item">
                    <a href="javascript:;" class="btn-address-edit list-separator-link text-warning">Edit</a>
                </li>
                <li class="list-inline-item">
                    <a href="javascript:;" class="btn-address-set-default list-separator-link text-success">Make Primary Address</a>
                </li>
                <li class="list-inline-item">
                    <a href="javascript:;" class="btn-address-remove list-separator-link text-danger">Remove</a>
                </li>
            `);
            addresses.find('[name*="is_default"]').html('false');

            const thisId = thisRow.data('id');
            thisRow.css({
                'border-right': '10px solid #377dff'
            });
            thisRow.find(`[name="${addressType}[${thisId}][is_default]"]`).html('true');
            thisRow.find(`.btn-address-set-default`).closest(`.list-inline-item`).remove();
        }

        $(document).on('click', '.btn-address-create', async function (e) {
            await $(`input[name="address_name"]`).val('');
            await $(`input[name="address_phone"]`).val('');
            await $(`textarea[name="address_full_address"]`).val('');

            await $(`#btn-address-save`).attr('data-address-type', $(this).closest('.row').attr('data-id'));
            await $(`#btn-address-save`).attr('data-id', '');
            await $(`#btn-address-save`).attr('data-is-default', 'false');
            await $(`#btn-address-save`).html('Create');

            await $(`#addressModal`).modal('show');
        });

        $(document).on('click', '.btn-address-edit', async function (e) {
            const thisButton = $(this);
            const thisRow = thisButton.closest('.address');
            const addressType = thisRow.closest('.card-body').closest('.row').attr('data-id');
            const thisId = thisRow.data('id');

            const name = $(`[name="${addressType}[${thisId}][name]"]`).html();
            const phone = $(`[name="${addressType}[${thisId}][phone]"]`).html();
            const full_address = $(`[name="${addressType}[${thisId}][full_address]"]`).html();
            const isDefault = $(`[name="${addressType}[${thisId}][is_default]"]`).html();

            await $(`input[name="address_name"]`).val(name);
            await $(`input[name="address_phone"]`).val(phone);
            await $(`textarea[name="address_full_address"]`).val(full_address);

            await $(`#btn-address-save`).attr('data-address-type', addressType);
            await $(`#btn-address-save`).attr('data-id', thisId);
            await $(`#btn-address-save`).attr('data-is-default', isDefault);
            await $(`#btn-address-save`).html('Save');

            await $("#addressModal").modal('show');
        });

        $(document).on('click', '#btn-address-save', async function (e) {
            var errors = [];
            const addressType = $(this).attr('data-address-type');
            const addresses = $(`[data-id="${addressType}"]`).find('.card-body');
            const thisId = $(this).attr("data-id") != "" ? $(this).attr("data-id") : (addresses.children().length && parseInt(addresses.children().last().data('id')) ? parseInt(addresses.children().last().data('id'))+1 : 1);

            const name = $(`input[name="address_name"]`);
            if (!name.val()) {
                errors.push(`<li class="list-pointer-item">Name not found</li>`);
            }

            const phone = $(`input[name="address_phone"]`);
            if (!phone.val()) {
                errors.push(`<li class="list-pointer-item">Phone not found</li>`);
            }

            const fullAddress = $(`textarea[name="address_full_address"]`);
            if (!fullAddress.val()) {
                errors.push(`<li class="list-pointer-item">Full address not found</li>`);
            }

            if (errors.length > 0) {
                $.confirm({
                    title: 'Oops!',
                    type: 'red',
                    content: `<ul class="list-pointer list-pointer-sm list-pointer-soft-bg-danger">${errors.join("")}</ul>`,
                    autoClose: 'close|5000',
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
                if ($(`#btn-address-save`).html() == 'Save') {
                    addresses.find(`[data-id="${thisId}"]`).html(`
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div name="${addressType}[${thisId}][name]" class="h3 mb-1">${ name.val() }</div>
                                <div name="${addressType}[${thisId}][phone]">${ phone.val() }</div>
                                <div name="${addressType}[${thisId}][full_address]" class="text-truncate">${ fullAddress.val() }</div>
                                <div name="${addressType}[${thisId}][is_default]" hidden="">${ $(`#btn-address-save`).attr('data-is-default') }</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="list-inline list-separator">
                                    <li class="list-inline-item">
                                        <a href="javascript:;" class="btn-address-edit list-separator-link text-warning">Edit</a>
                                    </li>
                                    ${
                                        $(`#btn-address-save`).attr('data-is-default') == 'true' ? `` : `
                                        <li class="list-inline-item">
                                            <a href="javascript:;" class="btn-address-set-default list-separator-link text-success">Make Primary Address</a>
                                        </li>
                                        `
                                    }
                                    <li class="list-inline-item">
                                        <a href="javascript:;" class="btn-address-remove list-separator-link text-danger">Remove</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    `);
                } else {
                    addresses.append(`
                        <div data-id="${thisId}" class="address mb-5" style="border-right: 10px solid ${ addresses.children().length+1 == 1 ? `#377dff` : `transparent` };">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                <div name="${addressType}[${thisId}][name]" class="h3 mb-1">${ name.val() }</div>
                                <div name="${addressType}[${thisId}][phone]" >${ phone.val() }</div>
                                <div name="${addressType}[${thisId}][full_address]" class="text-truncate">${ fullAddress.val() }</div>
                                    <div name="${addressType}[${thisId}][is_default]" hidden="">${ addresses.children().length+1 == 1 ? `true` : `false` }</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <ul class="list-inline list-separator">
                                        <li class="list-inline-item">
                                            <a href="javascript:;" class="btn-address-edit list-separator-link text-warning">Edit</a>
                                        </li>
                                        ${
                                            addresses.children().length+1 == 1 ? `` : `
                                            <li class="list-inline-item">
                                                <a href="javascript:;" class="btn-address-set-default list-separator-link text-success">Make Primary Address</a>
                                            </li>
                                            `
                                        }
                                        <li class="list-inline-item">
                                            <a href="javascript:;" class="btn-address-remove list-separator-link text-danger">Remove</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `);
                }

                await $("#addressModal").modal('hide');
            }
        });

        $(document).on('click', '.btn-address-set-default', async function (e) {
            const thisButton = $(this);
            const thisRow = thisButton.closest('.address');

            handleSetContactDefault(thisRow);
        });

        $(document).on('click', '.btn-address-remove', async function (e) {
            const thisButton = $(this);
            const thisRow = thisButton.closest('.address');
            const addressType = thisRow.closest('.card-body').closest('.row').attr('data-id');

            await $.confirm({
                title: 'Confirmation!',
                content: 'Do  you want to remove this list?',
                autoClose: 'cancel|10000',
                type: 'orange',
                buttons: {
                    cancel: {
                        text: 'Batal',
                        keys: ['esc'],
                        action: function () {
                        }
                    },
                    destroy: {
                        text: 'Ya, Hapus',
                        keys: ['enter'],
                        btnClass: 'btn-danger',
                        action: async function () {
                            const thisId = thisRow.data('id'); 
                            const isDefault = thisRow.find(`[name="${addressType}[${thisId}][is_default]"]`).html();
                            const nextRow = thisRow.next().length == 1 ? thisRow.next() : thisRow.prev();
                            if (isDefault == 'true' && nextRow.length == 1) {
                                handleSetContactDefault(nextRow);
                            }

                            thisRow.remove();
                        }
                    },
                }
            });
        });

        $('#group').change();
    })();
</script>
@endsection