<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class CustomerController extends Controller
{
    // view page all customer
    public function allCustomers()
    {
        $allCustomers = DB::table('customers')->get();
        return view('formcustomers.allcustomers', compact('allCustomers'));
    }

    // add Customer
    public function addCustomer()
    {
        // $data = DB::table('room_types')->get();
        $user = DB::table('users')->get();
        return view('formcustomers.addcustomer', compact('user'));
    }

    public function saveCustomer(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255',
            'phone_number' => 'required|string|max:255',
            'dob'          => 'nullable|date',
            'gender'       => 'nullable|in:Male,Female,Other',
            'national_id'  => 'nullable|string|max:255',
            'address'      => 'nullable|string',
            'country'      => 'nullable|string|max:255',
            'fileupload'   => 'required|file',
            'message'      => 'required|string|max:255',
            'status'       => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $photo = $request->file('fileupload');
            $file_name = rand() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('/assets/upload/'), $file_name);

            $customer = new Customer;
            $customer->name         = $request->name;
            $customer->email        = $request->email;
            $customer->ph_number    = $request->phone_number;
            $customer->dob          = $request->dob;
            $customer->gender       = $request->gender;
            $customer->national_id  = $request->national_id;
            $customer->address      = $request->address;
            $customer->country      = $request->country;
            $customer->fileupload   = $file_name;
            $customer->message      = $request->message;
            $customer->status       = $request->input('status', 1); // Set status from select value

            $customer->save();

            DB::commit();
            Toastr::success('Create new customer successfully :)', 'Success');
            return redirect()->route('form/allcustomers/page');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Add Customer fail :)', 'Error');
            return redirect()->back()->withInput();
        }
    }

    // Edit customer
    public function updateCustomer($bkg_customer_id)
    {
        $customerEdit = DB::table('customers')->where('bkg_customer_id', $bkg_customer_id)->first();
        return view('formcustomers.editcustomer', compact('customerEdit'));
    }

    // Update customer record
    public function updateRecord(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255',
            'phone_number' => 'required|string|max:255',
            'dob'          => 'nullable|date',
            'gender'       => 'nullable|in:Male,Female,Other',
            'national_id'  => 'nullable|string|max:255',
            'address'      => 'nullable|string',
            'country'      => 'nullable|string|max:255',
            'fileupload'   => 'nullable|file',
            'message'      => 'required|string|max:255',
            'status'       => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('fileupload')) {
                $photo = $request->file('fileupload');
                $file_name = rand() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('/assets/upload/'), $file_name);
            } else {
                $file_name = $request->hidden_fileupload;
            }

            $update = [
                'name'         => $request->name,
                'email'        => $request->email,
                'ph_number'    => $request->phone_number,
                'dob'          => $request->dob,
                'gender'       => $request->gender,
                'national_id'  => $request->national_id,
                'address'      => $request->address,
                'country'      => $request->country,
                'fileupload'   => $file_name,
                'message'      => $request->message,
                'status'       => $request->input('status', 1), // Update status from select value
            ];

            Customer::where('bkg_customer_id', $request->bkg_customer_id)->update($update);

            DB::commit();
            Toastr::success('Updated customer successfully :)', 'Success');
            return redirect()->route('form/allcustomers/page');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Update customer fail :)', 'Error');
            return redirect()->back()->withInput();
        }
    }

    // Delete customer record
    public function deleteRecord(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            // Make sure the request parameter matches your form/input name
            $customer = Customer::where('bkg_customer_id', $request->id)->first();
            // dd($customer);
            if (!$customer) {
                DB::rollback();
                Toastr::error('Customer not found.', 'Error');
                return redirect()->back();
            }
            // Optionally delete the uploaded file
            if ($customer->fileupload && file_exists(public_path('/assets/upload/' . $customer->fileupload))) {
                @unlink(public_path('/assets/upload/' . $customer->fileupload));
            }
            $customer->delete();
            DB::commit();
            Toastr::success('Customer deleted successfully :)', 'Success');
            return redirect()->route('form/allcustomers/page');
        } catch (\Throwable $e) {
            DB::rollback();
            Toastr::error('Delete customer fail :) ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
}
