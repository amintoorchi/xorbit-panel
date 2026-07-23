@php
    $datatime = \Carbon\Carbon::now();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="h-screen rounded-l-2xl bg-zinc-100 dark:bg-zinc-900 font-mono -tracking-wider text-md ">
    <flux:sidebar sticky collapsible class="bg-zinc-100 dark:bg-zinc-900">
        <flux:sidebar.header>
            <flux:sidebar.brand href="{{ route('home') }}" wire:navigate
                logo="{{ Auth::user()->avatar ?: asset('/images/avatar-placeholder.png') }}"
                logo:dark="{{ Auth::user()->avatar ?: asset('/images/avatar-placeholder.png') }}"
                name="{{ Auth::user()->name }}" />
            <flux:sidebar.collapse
                class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>
        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="{{ route('dashboard') }}" wire:navigate wire:current>Dashboard</flux:sidebar.item>
            <flux:sidebar.group expandable icon="cpu-chip" heading="Manage Servers" class="grid">
                <flux:sidebar.item icon="server-stack" href="{{ route('servers') }}" wire:current wire:navigate>Servers</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>
        <flux:sidebar.spacer />
        <flux:sidebar.nav class="gap-2">
            <flux:sidebar.item icon="cog-6-tooth" wire:navigate.hover href="{{ route('profile.edit') }}">Settings
            </flux:sidebar.item>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:sidebar.item as="button" type="submit" icon="arrow-left-start-on-rectangle" variant="danger" class="w-full">
                    Sign out
                </flux:sidebar.item>
            </form>
        </flux:sidebar.nav>
    </flux:sidebar>
    <flux:header>
        <flux:sidebar.collapse class="lg:hidden" />
        <flux:spacer />
        <flux:dropdown position="bottom" align="start">
            {{-- <flux:profile avatar="{{ Auth::user()->avatar ?: asset('images/avatar-placeholder.png') }}"
                name="{{ Auth::user()->name }}" /> --}}
                <span class="text-xs">
                    {{ $datatime }}
                </span>

            <flux:navmenu>
                <flux:navmenu.item href="{{ route('profile.edit') }}" wire:navigate.hover icon="cog">Settings
                </flux:navmenu.item>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <flux:navmenu.item as="button" type="submit" icon="arrow-left-start-on-rectangle" variant="danger"
                        class="w-full">
                        Sign out
                    </flux:navmenu.item>
                </form>
            </flux:navmenu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>