    @props([
        'type' => 'text',
        'label' => false,
        'value' => null,
        'name',
    ])
    <div class='space-y-3'>
        @if ($label)
            <label for='{{ $name }}' class='label'>{{ $label }}</label>
        @endif

        @if ($type === 'textarea')
            <textarea class='textarea' name="{{ $name }}" id="{{ $name }}"
                {{ $attributes }}>{{ old($name , $value) }}</textarea>
        @else
            <input type='{{ $type }}' class='input' id='{{ $name }}' name='{{ $name }}'
                value="{{ old($name , $value)}}" {{ $attributes }}>
        @endif
        <x-form.error name="{{ $name }}" />
    </div>
