@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Gebruikers beheren</h1>
        <div class="cookbook-card p-6 rounded-lg">
            @foreach($users as $user)
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <strong>{{ $user->name }}</strong> ({{ $user->email }}) - Role: {{ $user->role }}
                    </div>
                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Verwijderen</button>
                    </form>
                </div>
            @endforeach
            {{ $users->links() }}
        </div>
    </div>
@endsection
