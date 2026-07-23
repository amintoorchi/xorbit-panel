<div class="p-8">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Manage Your Servers') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage and control your added servers') }}</flux:subheading>
        <flux:separator variant="subtle" />


        <flux:modal name="add-server" flyout variant="floating" class="md:w-md" wire:close="resetForm">
            <form wire:submit="save" class="space-y-6">

                <div>
                    <flux:heading size="lg">Add New Server</flux:heading>
                    <flux:subheading>Add a new server to your control panel.</flux:subheading>
                </div>

                <flux:input wire:model.blur="name" label="Server Name" placeholder="Production Server" />
                <flux:input wire:model.blur="host" label="Host" placeholder="192.168.1.10" />
                <flux:input wire:model.blur="username" label="SSH Username" placeholder="root" />
                <flux:input wire:model.blur="port" type="number" label="SSH Port" placeholder="22" />
                <flux:input wire:model.blur="password" type="password" label="Password" placeholder="••••••••••••" />

                <div class="flex justify-end gap-2 pt-2">
                    <flux:modal.close>
                        <flux:button variant="ghost">
                            Cancel
                        </flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            Add Server
                        </span>

                        <span wire:loading wire:target="save">
                            Adding...
                        </span>
                    </flux:button>
                </div>

            </form>
        </flux:modal>

        <flux:modal name="show-credential" class="md:w-2xl">
            @if($createdServer)
                <div class="space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-900 dark:bg-zinc-800">
                            <flux:icon.server class="size-5 text-emerald-400" />
                        </div>
                        <div>
                            <flux:heading size="lg">{{ $createdServer->name }}</flux:heading>
                            <flux:subheading>Run this command on your server to connect the agent</flux:subheading>
                        </div>
                    </div>

                    <div x-data="{
                        copied: false,
                        command: {{ Js::from('curl -fsSL https://raw.githubusercontent.com/amintoorchi/xorbit-agent/main/install.sh | sudo bash -s ' . $createdServer->api_key) }},
                        copy() {
                            navigator.clipboard.writeText(this.command);
                            this.copied = true;
                            setTimeout(() => this.copied = false, 2000);
                        }
                    }" class="overflow-hidden rounded-xl border border-zinc-800 bg-zinc-950 shadow-lg">
                        <div class="flex items-center justify-between border-b border-zinc-800 bg-zinc-900 px-4 py-2.5">
                            <div class="flex items-center gap-2">
                                <span class="size-2.5 rounded-full bg-red-500/80"></span>
                                <span class="size-2.5 rounded-full bg-yellow-500/80"></span>
                                <span class="size-2.5 rounded-full bg-green-500/80"></span>
                                <span class="ml-2 font-mono text-xs text-zinc-500">bash</span>
                            </div>

                            <button @click="copy()"
                                class="flex items-center gap-1.5 rounded-md px-2 py-1 font-mono text-xs text-zinc-400 transition hover:bg-zinc-800 hover:text-zinc-200">
                                <template x-if="!copied">
                                    <span class="flex items-center gap-1.5">
                                        <flux:icon.clipboard class="size-3.5" />
                                        Copy
                                    </span>
                                </template>
                                <template x-if="copied">
                                    <span class="flex items-center gap-1.5 text-emerald-400">
                                        <flux:icon.check class="size-3.5" />
                                        Copied
                                    </span>
                                </template>
                            </button>
                        </div>

                        <div class="px-4 py-4">
                            <pre
                                class="whitespace-pre-wrap break-all font-mono text-[13px] leading-relaxed text-zinc-300"><span class="text-emerald-400">$</span> curl -fsSL https://raw.githubusercontent.com/amintoorchi/xorbit-agent/main/install.sh | sudo bash -s <span class="text-sky-400">{{ $createdServer->api_key }}</span></pre>
                        </div>
                    </div>

                    <flux:text size="sm" class="text-red-500">
                        This key will only be shown once. Please save it securely.
                    </flux:text>
                    <flux:text size="sm" class="text-zinc-500">
                        This key is unique to this server. Keep it private.
                    </flux:text>
                </div>
            @endif
        </flux:modal>

    </div>

    {{-- <x-partials.head :title='headerr' /> --}}

    <div class="container max-w-screen-xl mx-auto mt-10">

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <flux:text>Total Servers</flux:text>

                <flux:heading size="xl" class="mt-2">
                    {{ $this->count }}
                </flux:heading>

                <div class="mt-2 flex items-center gap-2">
                    <flux:icon.server variant="micro" class="text-blue-600 dark:text-blue-500" />

                    <span class="text-sm text-neutral-500">
                        Registered servers
                    </span>
                </div>
            </div>


            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <flux:text>Online Servers</flux:text>

                <flux:heading size="xl" class="mt-2">
                    10
                </flux:heading>

                <div class="mt-2 flex items-center gap-2">
                    <flux:icon.check-circle variant="micro" class="text-green-600 dark:text-green-500" />

                    <span class="text-sm text-green-600 dark:text-green-500">
                        83% uptime
                    </span>
                </div>
            </div>


            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <flux:text>Docker Containers</flux:text>

                <flux:heading size="xl" class="mt-2">
                    48
                </flux:heading>

                <div class="mt-2 flex items-center gap-2">
                    <flux:icon.cube variant="micro" class="text-purple-600 dark:text-purple-500" />

                    <span class="text-sm text-neutral-500">
                        Running containers
                    </span>
                </div>
            </div>

        </div>



        <div class="mt-20">
            <flux:modal.trigger name="add-server">
                <flux:button>New Server</flux:button>
            </flux:modal.trigger>
        </div>

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 px-7 py-4 mt-5">
            <div class="overflow-x-auto">
                <flux:table class="min-w-full m-auto ">

                    <flux:table.columns>
                        <flux:table.column>Name</flux:table.column>
                        <flux:table.column>Username</flux:table.column>
                        <flux:table.column>Host</flux:table.column>
                        <flux:table.column>Port</flux:table.column>
                        <flux:table.column align="center">
                            Manage
                        </flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($this->servers as $server)
                            <flux:table.row :key="$server->id">

                                <flux:table.cell class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21.75 17.25v-.228a4.5 4.5 0 0 0-.12-1.03l-2.268-9.64a3.375 3.375 0 0 0-3.285-2.602H7.923a3.375 3.375 0 0 0-3.285 2.602l-2.268 9.64a4.5 4.5 0 0 0-.12 1.03v.228m19.5 0a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3m19.5 0a3 3 0 0 0-3-3H5.25a3 3 0 0 0-3 3m16.5 0h.008v.008h-.008v-.008Zm-3 0h.008v.008h-.008v-.008Z" />
                                    </svg>

                                    <span class="whitespace-nowrap">
                                        {{ $server->name }}
                                    </span>
                                </flux:table.cell>

                                <flux:table.cell class="whitespace-nowrap">
                                    {{ $server->username }}
                                </flux:table.cell>

                                <flux:table.cell class="whitespace-nowrap">
                                    {{ $server->host }}
                                </flux:table.cell>

                                <flux:table.cell class="whitespace-nowrap">
                                    {{ $server->port }}
                                </flux:table.cell>

                                <flux:table.cell align="center">
                                    <flux:button icon="adjustments-horizontal" square size="sm" tooltip="Manage server"
                                        href="{{ route('server', $server->id) }}" wire:navigate />
                                </flux:table.cell>

                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>

                </flux:table>
            </div>
        </div>

    </div>
</div>