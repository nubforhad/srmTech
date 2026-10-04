@extends('admin.layouts.app')

@section('title', 'IP Access Rules')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                IP Access Rules
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Manage public IP based category and service access.
            </p>
        </div>
        <a href="{{ route('ip-access-rules.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
            + Add IP Rule
        </a>
    </div>
    {{-- Success Message --}}
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif
    {{-- Search --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('ip-access-rules.index') }}" class="flex flex-col gap-3 sm:flex-row">
            <div class="flex-1">
                <input  type="text"  name="search" value="{{ request('search') }}"
                    placeholder="Search by name, title or IP address..."
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
            </div>

            <button
                type="submit"
                class="rounded-lg bg-slate-800 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-900"
            >
                Search
            </button>

            @if(request('search'))

                <a
                    href="{{ route('ip-access-rules.index') }}"
                    class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Clear
                </a>

            @endif

        </form>

    </div>


    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            #
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            IP Address
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Name / Title
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Categories
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Services
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Status
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Expiry
                        </th>

                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($ipAccessRules as $index => $rule)

                        <tr class="transition hover:bg-slate-50">

                            {{-- Serial --}}
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">

                                {{ $ipAccessRules->firstItem() + $index }}

                            </td>


                            {{-- IP --}}
                            <td class="whitespace-nowrap px-4 py-4">

                                <div class="font-mono text-sm font-semibold text-slate-800">
                                    {{ $rule->ip_address }}
                                </div>

                            </td>


                            {{-- Name / Title --}}
                            <td class="px-4 py-4">

                                <div class="text-sm font-semibold text-slate-800">
                                    {{ $rule->name ?: '—' }}
                                </div>

                                @if($rule->title)

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $rule->title }}
                                    </div>

                                @endif

                            </td>


                            {{-- Categories --}}
                            <td class="whitespace-nowrap px-4 py-4 text-center">

                                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">

                                    {{ count($rule->category_ids ?? []) }}

                                    {{ count($rule->category_ids ?? []) === 1 ? 'Category' : 'Categories' }}

                                </span>

                            </td>


                            {{-- Services --}}
                            <td class="whitespace-nowrap px-4 py-4 text-center">

                                <span class="inline-flex items-center rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700">

                                    {{ count($rule->service_ids ?? []) }}

                                    {{ count($rule->service_ids ?? []) === 1 ? 'Service' : 'Services' }}

                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="whitespace-nowrap px-4 py-4 text-center">

                                @if($rule->isActive())

                                    <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">

                                        Active

                                    </span>

                                @else

                                    <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- Expiry --}}
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-600">

                                @if($rule->expires_at)

                                    <div>
                                        {{ $rule->expires_at->format('d M Y') }}
                                    </div>

                                    <div class="text-xs text-slate-400">
                                        {{ $rule->expires_at->format('h:i A') }}
                                    </div>

                                    @if($rule->expires_at->isPast())

                                        <span class="mt-1 inline-block text-xs font-semibold text-red-600">
                                            Expired
                                        </span>

                                    @endif

                                @else

                                    <span class="text-slate-400">
                                        No expiry
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="whitespace-nowrap px-4 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    {{-- Show --}}
                                    <a
                                        href="{{ route('ip-access-rules.show', $rule) }}"
                                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                                    >
                                        View
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('ip-access-rules.edit', $rule) }}"
                                        class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100"
                                    >
                                        Edit
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('ip-access-rules.destroy', $rule) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this IP access rule?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-6 py-12 text-center"
                            >

                                <div class="text-sm font-semibold text-slate-600">
                                    No IP access rules found.
                                </div>

                                <div class="mt-1 text-xs text-slate-400">
                                    Create an IP rule to assign categories and services.
                                </div>

                                <a
                                    href="{{ route('ip-access-rules.create') }}"
                                    class="mt-4 inline-flex rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                                >
                                    + Add IP Rule
                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($ipAccessRules->hasPages())

            <div class="border-t border-slate-200 px-4 py-4">

                {{ $ipAccessRules->links() }}

            </div>

        @endif

    </div>

</div>

@endsection