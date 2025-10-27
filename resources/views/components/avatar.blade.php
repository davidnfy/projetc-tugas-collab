<div class="border-t px-6 py-4 flex items-center space-x-3">
    @if(Auth::user()->avatar)
        <img src="{{ Auth::user()->avatar }}" class="w-10 h-10 rounded-full" alt="avatar">
    @else
        <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-white font-semibold">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>
    @endif

    <div class="flex-1">
        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
        <form action="{{ route('logout') }}" method="POST" class="mt-1">
            @csrf
            <button type="submit" class="text-xs text-red-500 hover:underline">Logout</button>
        </form>
    </div>
</div>
