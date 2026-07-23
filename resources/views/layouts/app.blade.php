<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main class="rounded-l-2xl bg-white dark:bg-zinc-950 !p-0">
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
