@extends('layouts.guest')
@section('content')
    <div class="flex items-center justify-center min-h-screen bg-[#EAF6FF]">
        <div class="w-full max-w-sm p-8 space-y-6 bg-white rounded-lg shadow-lg">

            <div class="flex flex-col items-center">
                <h1 style="font-family: 'Aldrich', sans-serif; font-size: 45px; font-weight: bold; color: #4B0082;" class="text-center mb-2">
                    Freshtrack
                </h1>
                <img src="{{ asset('images/logo.png') }}" alt="Freshtrack Logo" class="w-50 h-auto mb-2">
                <h2 class="text-3xl font-bold text-gray-700 mt-2">Login</h2>
            </div>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <input id="username" type="text" name="username" placeholder="Nama Pengguna" value="{{ old('username') }}" required autofocus autocomplete="username"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div>
                    <input id="password" type="password" name="password" placeholder="Kata Sandi" required autocomplete="current-password"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div>
                    <button type="submit"
                            class="w-full px-4 py-3 font-semibold text-white bg-sky-500 rounded-md hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition duration-200">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection