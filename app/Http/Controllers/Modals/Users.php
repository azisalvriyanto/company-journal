<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Models\OperationType;
use App\Models\StorageOperationType;
use App\Models\User;
use App\Models\Contact;

use Illuminate\Http\Response;

use DB;
use Validator;

class Users extends Controller
{
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'group'                 => 'required|in:' . collect(User::GROUPS)->pluck('id')->implode(','),
            'parent_company'        => 'required||exists:users,id',
            'name'                  => 'required|string',
            'email'                 => 'nullable|string|email|max:255|unique:users',
            'password'              => 'nullable|min:8|required_with:password_confirmation|string|confirmed',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                $query                      = new User;
                $query->name                = $request->name;
                $query->group               = $request->group ?? 'User';
                $query->parent_company_id   = $request->parent_company ?? NULL;
                $query->code                = $request->code ?? NULL;
                $query->email               = $request->email ?? NULL;
                if ($query->email) {
                    $query->password        = $request->password ?? NULL;
                }
                $query->is_enable           = $request->is_enable ?? 0;
                $query->save();

                if ($query->group == 'Storage') {
                    $operationTypes = OperationType::query()->whereIsEnable(TRUE)->get();
                    foreach ($operationTypes as $operationType) {
                        $queryStorageOperationType                      = new StorageOperationType;
                        $queryStorageOperationType->storage_id          = $query->id;
                        $queryStorageOperationType->operation_type_id   = $operationType->id;
                        $queryStorageOperationType->name                = $operationType->group . ' - ' . $operationType->name;
                        $queryStorageOperationType->prefix_format       = NULL;
                        $queryStorageOperationType->suffix_format       = NULL;
                        $queryStorageOperationType->sequence_size       = 10;
                        $queryStorageOperationType->is_enable           = 1;
                        $queryStorageOperationType->save();
                    }
                }

                if ($request->contact_address) {
                    foreach ($request->contact_address as $contactAddress) {
                        $queryContactAddress                = new Contact;
                        $queryContactAddress->owner_id      = $query->id;
                        $queryContactAddress->group         = 'Contact';
                        $queryContactAddress->name          = array_key_exists('name', $contactAddress)         ? $contactAddress['name']           : NULL;
                        $queryContactAddress->phone         = array_key_exists('phone', $contactAddress)        ? $contactAddress['phone']          : NULL;
                        $queryContactAddress->full_address  = array_key_exists('full_address', $contactAddress) ? $contactAddress['full_address']   : NULL;
                        $queryContactAddress->save();

                        if (array_key_exists('is_default', $contactAddress) && $contactAddress['is_default'] == 'true') {
                            $query->default_contact_address_id = $queryContactAddress->id;
                        }
                    }
                }

                if ($request->billing_address) {
                    foreach ($request->billing_address as $billingAddress) {
                        $queryBillingAddress                = new Contact;
                        $queryBillingAddress->owner_id      = $query->id;
                        $queryBillingAddress->group         = 'Billing';
                        $queryBillingAddress->name          = array_key_exists('name', $billingAddress)         ? $billingAddress['name']           : NULL;
                        $queryBillingAddress->phone         = array_key_exists('phone', $billingAddress)        ? $billingAddress['phone']          : NULL;
                        $queryBillingAddress->full_address  = array_key_exists('full_address', $billingAddress) ? $billingAddress['full_address']   : NULL;
                        $queryBillingAddress->save();

                        if (array_key_exists('is_default', $billingAddress) && $billingAddress['is_default'] == 'true') {
                            $query->default_billing_address_id = $queryBillingAddress->id;
                        }
                    }
                }

                if ($request->shipping_address) {
                    foreach ($request->shipping_address as $shippingAddress) {
                        $queryShippingAddress                = new Contact;
                        $queryShippingAddress->owner_id      = $query->id;
                        $queryShippingAddress->group         = 'Shipping';
                        $queryShippingAddress->name          = array_key_exists('name', $shippingAddress)         ? $shippingAddress['name']           : NULL;
                        $queryShippingAddress->phone         = array_key_exists('phone', $shippingAddress)        ? $shippingAddress['phone']          : NULL;
                        $queryShippingAddress->full_address  = array_key_exists('full_address', $shippingAddress) ? $shippingAddress['full_address']   : NULL;
                        $queryShippingAddress->save();

                        if (array_key_exists('is_default', $shippingAddress) && $shippingAddress['is_default'] == 'true') {
                            $query->default_shipping_address_id = $queryShippingAddress->id;
                        }
                    }
                }

                $query->save();

                $user = User::query()->find($query->id);
                if ($request->owner_types) {
                    $user->ownerTypes()->sync($request->owner_types);
                }

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'User created in successfully.',
                    'data'      => $query,
                    'errors'    => [],
                ];
            } catch (\Exception $e) {
                DB::rollback();
                $response = [
                    'status'    => 500,
                    'message'   => $e->getMessage(),
                    'data'      => NULL,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'User failed to create.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function update($request, $id)
    {
        $query = User::query()->find($id);
        if ($query) {
            $query = $query;
        } else {
            $response = [
                'status'    => 404,
                'message'   => 'User not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        $validator = Validator::make($request->all(), [
            'group'                 => 'required|in:' . collect(User::GROUPS)->pluck('id')->implode(','),
            'parent_company'        => 'required||exists:users,id',
            'name'                  => 'required|string',
            'email'                 => 'nullable|string|email|max:255|unique:users,email,'.$query->id,
            'password'              => 'nullable|min:8|required_with:password_confirmation|string|confirmed',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();

                if ($request->group == 'Storage') {
                    if ($query->group != $request->group) {
                        $operationTypes = OperationType::query()->whereIsEnable(TRUE)->get();
                        foreach ($operationTypes as $operationType) {
                            $queryStorageOperationType                      = new StorageOperationType;
                            $queryStorageOperationType->storage_id          = $query->id;
                            $queryStorageOperationType->operation_type_id   = $operationType->id;
                            $queryStorageOperationType->name                = $operationType->group . ' ~> ' . $operationType->name;
                            $queryStorageOperationType->prefix_format       = NULL;
                            $queryStorageOperationType->suffix_format       = NULL;
                            $queryStorageOperationType->sequence_size       = 10;
                            $queryStorageOperationType->is_enable           = 1;
                            $queryStorageOperationType->save();
                        }
                    }
                } else {
                    if ($query->group == 'Storage') {
                        StorageOperationType::query()
                        ->whereStorageId($query->id)
                        ->delete();
                    }
                }

                $query->name                = $request->name;
                $query->group               = $request->group ?? 'User';
                $query->parent_company_id   = $request->parent_company ?? NULL;
                $query->code                = $request->code ?? NULL;
                $query->email               = $request->email ?? NULL;
                if ($query->email) {
                    if ($request->password) {
                        $query->password    = $request->password;
                    }
                } else {
                    $query->password    = NULL;
                }
                $query->is_enable           = $request->is_enable ?? 0;
                $query->save();

                $query->default_contact_address_id = NULL;
                $contactAddressIds = $query->contacts->where('group', 'Contact')->pluck('id', 'id')->toArray();
                if ($request->contact_address) {
                    foreach ($request->contact_address as $contactAddressId => $contactAddress) {
                        $queryContactAddress = Contact::query()
                        ->whereOwnerId($query->id)
                        ->whereId($contactAddressId)
                        ->first();
                        if ($queryContactAddress == NULL) {
                            $queryContactAddress            = new Contact;
                            $queryContactAddress->owner_id  = $query->id;
                            $queryContactAddress->group     = 'Contact';
                        } else {
                            $queryContactAddress->group         = 'Contact';
                        }

                        $queryContactAddress->name          = array_key_exists('name', $contactAddress)         ? $contactAddress['name']           : NULL;
                        $queryContactAddress->phone         = array_key_exists('phone', $contactAddress)        ? $contactAddress['phone']          : NULL;
                        $queryContactAddress->full_address  = array_key_exists('full_address', $contactAddress) ? $contactAddress['full_address']   : NULL;
                        $queryContactAddress->save();

                        if (array_key_exists('is_default', $contactAddress) && $contactAddress['is_default'] == 'true') {
                            $query->default_contact_address_id = $queryContactAddress->id;
                        }

                        if (in_array($queryContactAddress->id, $contactAddressIds)) {
                            unset($contactAddressIds[$queryContactAddress->id]);
                        }
                    }
                }

                if ($contactAddressIds) {
                    Contact::query()
                    ->whereOwnerId($query->id)
                    ->whereIn('id', $contactAddressIds)
                    ->delete();
                }

                $query->default_billing_address_id = NULL;
                $billingAddressIds = $query->contacts->where('group', 'Billing')->pluck('id', 'id')->toArray();
                if ($request->billing_address) {
                    foreach ($request->billing_address as $billingAddressId => $billingAddress) {
                        $queryBillingAddress = Contact::query()
                        ->whereOwnerId($query->id)
                        ->whereId($billingAddressId)
                        ->first();
                        if ($queryBillingAddress == NULL) {
                            $queryBillingAddress            = new Contact;
                            $queryBillingAddress->owner_id  = $query->id;
                            $queryBillingAddress->group     = 'Billing';
                        } else {
                            $queryBillingAddress->group     = 'Billing';
                        }

                        $queryBillingAddress->name          = array_key_exists('name', $billingAddress)         ? $billingAddress['name']           : NULL;
                        $queryBillingAddress->phone         = array_key_exists('phone', $billingAddress)        ? $billingAddress['phone']          : NULL;
                        $queryBillingAddress->full_address  = array_key_exists('full_address', $billingAddress) ? $billingAddress['full_address']   : NULL;
                        $queryBillingAddress->save();

                        if (array_key_exists('is_default', $billingAddress) && $billingAddress['is_default'] == 'true') {
                            $query->default_billing_address_id = $queryBillingAddress->id;
                        }

                        if (in_array($queryBillingAddress->id, $billingAddressIds)) {
                            unset($billingAddressIds[$queryBillingAddress->id]);
                        }
                    }
                }

                if ($billingAddressIds) {
                    Contact::query()
                    ->whereOwnerId($query->id)
                    ->whereIn('id', $billingAddressIds)
                    ->delete();
                }

                $query->default_shipping_address_id = NULL;
                $shippingAddressIds = $query->contacts->where('group', 'Shipping')->pluck('id', 'id')->toArray();
                if ($request->shipping_address) {
                    foreach ($request->shipping_address as $shippingAddressId => $shippingAddress) {
                        $queryShippingAddress = Contact::query()
                        ->whereOwnerId($query->id)
                        ->whereId($shippingAddressId)
                        ->first();
                        if ($queryShippingAddress == NULL) {
                            $queryShippingAddress            = new Contact;
                            $queryShippingAddress->owner_id  = $query->id;
                            $queryShippingAddress->group     = 'Shipping';
                        } else {
                            $queryShippingAddress->group     = 'Shipping';
                        }
    
                        $queryShippingAddress->name          = array_key_exists('name', $shippingAddress)         ? $shippingAddress['name']           : NULL;
                        $queryShippingAddress->phone         = array_key_exists('phone', $shippingAddress)        ? $shippingAddress['phone']          : NULL;
                        $queryShippingAddress->full_address  = array_key_exists('full_address', $shippingAddress)   ? $shippingAddress['full_address']   : NULL;
                        $queryShippingAddress->save();
    
                        if (array_key_exists('is_default', $shippingAddress) && $shippingAddress['is_default'] == 'true') {
                            $query->default_shipping_address_id = $queryShippingAddress->id;
                        }
    
                        if (in_array($queryShippingAddress->id, $shippingAddressIds)) {
                            unset($shippingAddressIds[$queryShippingAddress->id]);
                        }
                    }
                }

                if ($shippingAddressIds) {
                    Contact::query()
                    ->whereOwnerId($query->id)
                    ->whereIn('id', $shippingAddressIds)
                    ->delete();
                }

                $query->save();

                $user = User::query()->find($query->id);
                if ($request->owner_types) {
                    $user->ownerTypes()->sync($request->owner_types);
                }

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'User updated in successfully.',
                    'data'      => $query,
                    'errors'    => [],
                ];
            } catch (\Exception $e) {
                DB::rollback();
                $response = [
                    'status'    => 500,
                    'message'   => $e->getMessage(),
                    'data'      => $query,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 500,
                'message'   => 'User failed to update.',
                'data'      => NULL,
                'errors'    => $validator->errors()->getMessages(),
            ];
        }

        return $response;
    }

    public function destroy($request, $id)
    {
        $query = User::query()->find($id);
        if ($query) {
            try {
                DB::beginTransaction();

                $query->delete();

                DB::commit();
                $response = [
                    'status'    => 200,
                    'message'   => 'User deleted in successfully.',
                    'data'      => NULL,
                    'errors'    => [],
                ];
            } catch (\Exception $e) {
                DB::rollback();
                $response = [
                    'status'    => 500,
                    'message'   => $e->getMessage(),
                    'data'      => $query,
                    'errors'    => [],
                ];
            }
        } else {
            $response = [
                'status'    => 404,
                'message'   => 'User not found.',
                'data'      => NULL,
                'errors'    => [],
            ];
        }

        return $response;
    }
}