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

    <div class="mt-6 px-8 py-4 max-w-screen-xl m-auto mt-13">
        @switch($tab)
            @case('overview')

                <div class="space-y-6">

                    {{-- Header --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <flux:heading size="xl">{{ $server->name }}</flux:heading>

                            <flux:text class="mt-1 text-zinc-500">
                                {{ $server->host }} :
                            </flux:text>
                        </div>

                        @php
                            $statusColor = match ($server->status) {
                                'paired' => 'green',
                                'pending' => 'amber',
                                'offline' => 'red',
                                default => 'zinc',
                            };
                        @endphp
                        <flux:badge :color="$statusColor">
                            {{ ucfirst($server->status) }}
                        </flux:badge>
                    </div>

                    {{-- Stats --}}
                    <div class="grid gap-4 md:grid-cols-4">

                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                            <flux:text>CPU Usage</flux:text>

                            <flux:heading size="xl" class="mt-2">
                                @if($server->status === 'pending')
                                    <span class="text-zinc-400 dark:text-zinc-600 text-[22px]">Unavalble</span>
                                @else
                                    14%
                                @endif
                            </flux:heading>
                        </div>

                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                            <flux:text>Memory</flux:text>

                            <flux:heading size="xl" class="mt-2">
                                @if($server->status === 'pending')
                                    <span class="text-zinc-400 dark:text-zinc-600 text-[22px]">Unavalble</span>
                                @else
                                    2.4 / 8 GB
                                @endif
                            </flux:heading>
                        </div>

                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                            <flux:text>Disk</flux:text>

                            <flux:heading size="xl" class="mt-2">
                                @if($server->status === 'pending')
                                    <span class="text-zinc-400 dark:text-zinc-600 text-[22px]">Unavalble</span>
                                @else
                                    48 / 120 GB
                                @endif
                            </flux:heading>
                        </div>

                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-5">
                            <flux:text>Uptime</flux:text>

                            <flux:heading size="xl" class="mt-2">
                                @if($server->status === 'pending')
                                    <span class="text-zinc-400 dark:text-zinc-600 text-[22px]">Unavalble</span>
                                @else
                                    8 Days
                                @endif
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
                                    Paired At
                                </flux:text>

                                <p class="mt-1 font-medium">
                                    @if($server->paired_at)
                                        {{ $server->paired_at->diffForHumans() }}
                                    @else
                                        <span class="text-zinc-400 dark:text-zinc-600 font-normal">Not paired yet</span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <flux:text class="text-zinc-500">
                                    Last Seen At
                                </flux:text>

                                <p class="mt-1 font-medium">
                                    @if($server->last_seen_at)
                                        {{ $server->last_seen_at->diffForHumans() }}
                                    @else
                                        <span class="text-zinc-400 dark:text-zinc-600 font-normal">Never</span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <flux:text class="text-zinc-500">
                                    Agent Version
                                </flux:text>

                                <p class="mt-1 font-medium">
                                    @if($server->agent_version)
                                        {{ $server->agent_version }}
                                    @else
                                        <span class="text-zinc-400 dark:text-zinc-600 font-normal">Not connected</span>
                                    @endif
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