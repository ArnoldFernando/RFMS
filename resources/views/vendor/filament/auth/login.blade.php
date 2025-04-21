<x-filament::page>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-sm p-6 bg-white rounded shadow-md">
            <h2 class="mb-4 text-2xl font-bold text-center">Custom Login</h2>
            <form method="POST" action="{{ route('filament.auth.login') }}">
                @csrf
                <x-filament::input name="email" label="Email Address" type="email" required autofocus />
                <x-filament::input name="password" label="Password" type="password" required class="mt-4" />
                <x-filament::button type="submit" class="mt-4 w-full">Login</x-filament::button>
            </form>
        </div>
    </div>
</x-filament::page>
