@extends('layouts.admin')

@section('title', 'Category Hierarchy')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Category Hierarchy</h2>
    <ul class="list-group">
        @foreach ($categories as $parent)
            <li class="list-group-item">
                <strong>{{ $parent->name }}</strong>
                @if ($parent->children->isNotEmpty())
                    <ul class="list-group mt-2">
                        @foreach ($parent->children as $child)
                            <li class="list-group-item ms-4">{{ $child->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>
@endsection
