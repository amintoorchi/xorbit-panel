<div class="mb-5">
    <flux:header class="block! max-w-full m-auto lg:bg-white dark:bg-black rounded-tl-2xl border-b border-zinc-200 dark:border-zinc-700">
        <flux:navbar scrollable>
                <flux:navbar.item
                    wire:click="$set('tab', 'overview')"
                    :current="$tab === 'overview'"
                >
                    Overview
                </flux:navbar.item>

                <flux:navbar.item
                    wire:click="$set('tab', 'docker')"
                    :current="$tab === 'docker'"
                >
                    Docker
                </flux:navbar.item>

                <flux:navbar.item
                    wire:click="$set('tab', 'logs')"
                    :current="$tab === 'logs'"
                >
                    Logs
                </flux:navbar.item>

        </flux:navbar>
    </flux:header>

    <div class="mt-6 px-8 py-4 max-w-screen-xl m-auto mt-18">
        @switch($tab)
            @case('overview')

                <div class="space-y-6">

                    {{-- Header --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <flux:heading size="xl">{{ $server->name }}</flux:heading>

                            <flux:text class="mt-1 text-zinc-500">
                                {{ $server->host }}:{{ $server->port }}
                            </flux:text>
                        </div>

                        <flux:badge color="green">
                            Online
                        </flux:badge>
                    </div>

                    {{-- Stats --}}
                    <div class="grid gap-4 md:grid-cols-4">

                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                            <flux:text>CPU Usage</flux:text>

                            <flux:heading size="xl" class="mt-2">
                                14%
                            </flux:heading>
                        </div>

                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                            <flux:text>Memory</flux:text>

                            <flux:heading size="xl" class="mt-2">
                                2.4 / 8 GB
                            </flux:heading>
                        </div>

                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                            <flux:text>Disk</flux:text>

                            <flux:heading size="xl" class="mt-2">
                                48 / 120 GB
                            </flux:heading>
                        </div>

                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                            <flux:text>Uptime</flux:text>

                            <flux:heading size="xl" class="mt-2">
                                8 Days
                            </flux:heading>
                        </div>

                    </div>

                    {{-- Information --}}
                    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700">

                        <div class="border-b border-zinc-200 dark:border-zinc-700 px-6 py-4">
                            <flux:heading size="lg">
                                Server Information
                            </flux:heading>
                        </div>

                        <div class="grid gap-6 p-6 md:grid-cols-2">

                            <div>
                                <flux:text class="text-zinc-500">
                                    Host
                                </flux:text>

                                <p class="mt-1 font-medium">
                                    {{ $server->host }}
                                </p>
                            </div>

                            <div>
                                <flux:text class="text-zinc-500">
                                    SSH Port
                                </flux:text>

                                <p class="mt-1 font-medium">
                                    {{ $server->port }}
                                </p>
                            </div>

                            <div>
                                <flux:text class="text-zinc-500">
                                    Username
                                </flux:text>

                                <p class="mt-1 font-medium">
                                    {{ $server->username }}
                                </p>
                            </div>

                            <div>
                                <flux:text class="text-zinc-500">
                                    Added
                                </flux:text>

                                <p class="mt-1 font-medium">
                                    {{ $server->created_at->diffForHumans() }}
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            @break

            @case('docker')
                Docker
                @break

            @case('logs')
                logs
                @break
        @endswitch
    </div>
</div>