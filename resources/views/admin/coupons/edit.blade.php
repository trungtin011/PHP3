@extends('layouts.admin')

@section('title', 'Edit Coupon')

@section('content')
<div class="container mt-5">
    <h2>Edit Coupon</h2>
    <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ $coupon->code }}" required>
        </div>
        <div class="form-group">
            <label for="discount_type">Discount Type</label>
            <select name="discount_type" id="discount_type" class="form-control" required>
                <option value="percentage" {{ $coupon->discount_type === 'percentage' ? 'selected' : '' }}>Percentage</option>
                <option value="fixed" {{ $coupon->discount_type === 'fixed' ? 'selected' : '' }}>Fixed</option>
            </select>
        </div>
        <div class="form-group">
            <label for="discount_value">Discount Value</label>
            <input type="number" name="discount_value" id="discount_value" class="form-control" value="{{ $coupon->discount_value }}" required>
        </div>
        <div class="form-group">
            <label for="usage_limit">Usage Limit</label>
            <input type="number" name="usage_limit" id="usage_limit" class="form-control" value="{{ $coupon->usage_limit }}" required>
        </div>
        <div class="form-group">
            <label for="expires_at">Expires At</label>
            <input type="date" name="expires_at" id="expires_at" class="form-control" value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '' }}">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
