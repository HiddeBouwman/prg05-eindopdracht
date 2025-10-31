@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Welkom {{ Auth::user()->name }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('admin.users') }}" class="cookbook-card p-6 rounded-lg text-center">
                <h2 class="text-xl font-semibold">Gebruikers beheren</h2>
            </a>
            <a href="{{ route('admin.recipes') }}" class="cookbook-card p-6 rounded-lg text-center">
                <h2 class="text-xl font-semibold">Recepten beheren</h2>
            </a>
        </div>
    </div>
@endsection
