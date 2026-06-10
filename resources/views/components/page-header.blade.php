<div class="mb-5">
    <h1 class="text-[1.375rem] font-bold text-slate-800 leading-tight tracking-tight">
        {{ $title }}
    </h1>
    @if(isset($subtitle) && $subtitle)
    <p class="text-sm text-slate-500 mt-0.5 font-normal">{{ $subtitle }}</p>
    @endif
</div>
