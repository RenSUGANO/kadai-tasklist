@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-8">
            @if (Auth::id() == $user->id)
                {{-- 投稿フォーム --}}
                @include('tasks.show')
            @endif
            {{-- 投稿一覧 --}}
            @include('tasks.create')
        </div>
    </div>
@endsection