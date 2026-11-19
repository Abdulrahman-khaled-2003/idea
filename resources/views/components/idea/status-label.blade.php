@props([
    'status' => 'pending',
    'class'
])

@php
$class = "inline-block rounded-full border px-2 py-1 text-xs font-medium";
    if ($status === 'pending') {
        $class .= ' bg-yellow-500/10 text-yellow-500 border-yellow-500/20';
    }

    if ($status === 'in_progress') {
        $class .= ' bg-blue-500/10 text-blue-500 border-blue-500/20';
    }

    if ($status === 'completed') {
        $class .= ' bg-primary/10 text-primary border-primary/20';
    }
@endphp

<div class="mt-2">
<span {{ $attributes(['class' => $class]) }}>
    {{ $slot }}
</span>
</div>
