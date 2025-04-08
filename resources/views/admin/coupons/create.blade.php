@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('content')
<div class="container mt-5">
    <h2>Create Coupon</h2>
    <form action="{{ route('coupons.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" name="code" id="code" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="discount_type">Discount Type</label>
            <select name="discount_type" id="discount_type" class="form-control" required>
                <option value="percentage">Percentage</option>
                <option value="fixed">Fixed</option>
            </select>
        </div>
        <div class="form-group">
            <label for="discount_value">Discount Value</label>
            <input type="number" name="discount_value" id="discount_value" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="usage_limit">Usage Limit</label>
            <input type="number" name="usage_limit" id="usage_limit" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="expires_at">Expires At</label>
            <input type="date" name="expires_at" id="expires_at" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Create</button>
    </form>
</div>
@endsection
