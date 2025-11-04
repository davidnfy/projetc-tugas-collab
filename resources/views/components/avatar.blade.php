@props(['user' => Auth::user()])

<div class="border-t px-6 py-4 flex items-center space-x-3">
    @if ($user)
        @if (!empty($user->avatar))
            <img src="{{ $user->avatar }}" 
                 alt="Avatar" 
                 class="w-10 h-10 rounded-full object-cover border border-gray-300">
        @else
            <div class="w-10 h-10 rounded-full bg-gray-500 flex items-center justify-center text-white font-bold text-lg">
                {{ strtoupper(substr($user->name ?? $user->nama ?? '', 0, 1)) }}
            </div>
        @endif

        <div class="flex flex-col flex-1">
            <p class="text-sm font-semibold text-gray-800">{{ $user->name ?? $user->nama ?? '' }}</p>
            <!-- <p class="text-xs text-gray-500 truncate">{{ $user->email ?? '' }}</p> -->
            
            <form action="{{ route('logout') }}" method="POST" class="mt-1">
                @csrf
                <button type="submit" 
                        class="text-xs text-red-500 hover:text-red-600 hover:underline transition">
                    Logout
                </button>
            </form>
        </div>
    @else
        <a href="{{ route('login') }}" 
           class="text-blue-600 hover:underline text-sm font-medium">
           Login
        </a>
    @endif
</div>
