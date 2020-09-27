<div>
    {{-- <h1>Hello World!</h1>
    <h2>C'est génial Livewire !</h2> --}}

    <div style="text-align: center">
        <button wire:click="increment">+</button>
        <h1>{{ $count }}</h1>
        <button wire:click="decrement">-</button>
    </div>
</div>
