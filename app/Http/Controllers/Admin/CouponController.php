<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'status' => 'boolean',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'description' => $request->description,
            'min_spend' => $request->min_spend,
            'max_discount' => $request->max_discount,
            'expiry_date' => $request->expiry_date,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'status' => 'boolean',
        ]);

        $coupon->update([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'description' => $request->description,
            'min_spend' => $request->min_spend,
            'max_discount' => $request->max_discount,
            'expiry_date' => $request->expiry_date,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
