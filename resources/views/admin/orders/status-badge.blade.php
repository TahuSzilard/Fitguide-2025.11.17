@php
    $colors = [
        'pending' => 'bg-yellow-100 text-yellow-700',
        'completed' => 'bg-green-100 text-green-700',
        'cancelled' => 'bg-red-100 text-red-700',
    ];
@endphp

<span class="px-3 py-1 rounded-full text-xs {{ $colors[$status] ?? 'bg-gray-200 text-gray-600' }}">
    {{ ucfirst($status) }}
</span>
