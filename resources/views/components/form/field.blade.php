    @props([
        'type' => 'title',
        'label',
        'name',
    ])
    <div class='space-y-2'>
        <label for='{{ $name }}' class='label'>{{ $label }}</label>
        <input type='{{ $type }}' class='input' id='{{ $name }}' name='{{ $name }}' value={{ old($name) }}>
        <x-form.error name={{$name}} />
    </div>
