@extends('admin.layouts.app')

@section('title', 'IP Access Rule Details')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                IP Access Rule Details
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                View assigned categories, services and access information.
            </p>
        </div>


        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('ip-access-rules.edit', $ipAccessRule) }}"
                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Edit
            </a>

            <a
                href="{{ route('ip-access-rules.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- Basic Information --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">

            <h2 class="text-lg font-semibold text-slate-800">
                Basic Information
            </h2>

        </div>


        <div class="grid grid-cols-1 gap-6 p-5 md:grid-cols-2 lg:grid-cols-3">

            {{-- Name --}}
            <div>

                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Name
                </div>

                <div class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $ipAccessRule->name ?: '—' }}
                </div>

            </div>


            {{-- IP --}}
            <div>

                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Public IP Address
                </div>

                <div class="mt-1 font-mono text-sm font-semibold text-slate-800">
                    {{ $ipAccessRule->ip_address }}
                </div>

            </div>


            {{-- Title --}}
            <div>

                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Title
                </div>

                <div class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $ipAccessRule->title ?: '—' }}
                </div>

            </div>


            {{-- Subtitle --}}
            <div>

                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Subtitle
                </div>

                <div class="mt-1 text-sm text-slate-700">
                    {{ $ipAccessRule->subtitle ?: '—' }}
                </div>

            </div>


            {{-- Status --}}
            <div>

                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Status
                </div>

                <div class="mt-1">

                    @if($ipAccessRule->isActive())

                        <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                            Active
                        </span>

                    @else

                        <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                            Inactive
                        </span>

                    @endif

                </div>

            </div>


            {{-- Expiry --}}
            <div>

                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Expiry
                </div>

                <div class="mt-1 text-sm text-slate-700">

                    @if($ipAccessRule->expires_at)

                        {{ $ipAccessRule->expires_at->format('d M Y, h:i A') }}

                        @if($ipAccessRule->expires_at->isPast())

                            <div class="mt-1 text-xs font-semibold text-red-600">
                                Expired
                            </div>

                        @endif

                    @else

                        No expiry

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Content --}}
    @if($ipAccessRule->paragraph || $ipAccessRule->details)

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-lg font-semibold text-slate-800">
                    Content Information
                </h2>

            </div>


            <div class="space-y-6 p-5">

                @if($ipAccessRule->paragraph)

                    <div>

                        <h3 class="text-sm font-semibold text-slate-700">
                            Paragraph
                        </h3>

                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">
                            {{ $ipAccessRule->paragraph }}
                        </p>

                    </div>

                @endif


                @if($ipAccessRule->details)

                    <div>

                        <h3 class="text-sm font-semibold text-slate-700">
                            Details
                        </h3>

                        <div class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">
                            {{ $ipAccessRule->details }}
                        </div>

                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- Categories --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">

            <div>

                <h2 class="text-lg font-semibold text-slate-800">
                    Assigned Categories
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Categories assigned to this IP rule.
                </p>

            </div>


            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                {{ $categories->count() }}
            </span>

        </div>


        <div class="p-5">

            @if($categories->count())

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                    @foreach($categories as $category)

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                            <div class="flex items-start justify-between gap-3">

                                <div>

                                    <h3 class="font-semibold text-slate-800">
                                        {{ $category->title }}
                                    </h3>

                                    @if($category->subtitle)

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $category->subtitle }}
                                        </p>

                                    @endif

                                </div>


                                @if($category->status)

                                    <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                        Active
                                    </span>

                                @else

                                    <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                        Inactive
                                    </span>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="rounded-lg bg-slate-50 px-4 py-4 text-sm text-slate-500">
                    No categories assigned.
                </div>

            @endif

        </div>

    </div>


    {{-- Services --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">

            <div>

                <h2 class="text-lg font-semibold text-slate-800">
                    Assigned Services
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Services assigned to this IP rule.
                </p>

            </div>


            <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700">
                {{ $services->count() }}
            </span>

        </div>


        <div class="p-5">

            @if($services->count())

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                    @foreach($services as $service)

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0">

                                    <h3 class="font-semibold text-slate-800">
                                        {{ $service->title }}
                                    </h3>

                                    @if($service->category)

                                        <p class="mt-1 text-xs text-blue-600">
                                            {{ $service->category->title }}
                                        </p>

                                    @endif

                                    @if($service->sub_title)

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $service->sub_title }}
                                        </p>

                                    @endif

                                </div>


                                @if($service->status)

                                    <span class="shrink-0 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                        Active
                                    </span>

                                @else

                                    <span class="shrink-0 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                        Inactive
                                    </span>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="rounded-lg bg-slate-50 px-4 py-4 text-sm text-slate-500">
                    No services assigned.
                </div>

            @endif

        </div>

    </div>


    {{-- Remark --}}
    @if($ipAccessRule->remark)

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="p-5">

                <h2 class="text-lg font-semibold text-slate-800">
                    Remark
                </h2>

                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">
                    {{ $ipAccessRule->remark }}
                </p>

            </div>

        </div>

    @endif


    {{-- Bottom Actions --}}
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

        <form
            action="{{ route('ip-access-rules.destroy', $ipAccessRule) }}"
            method="POST"
            onsubmit="return confirm('Are you sure you want to delete this IP access rule?');"
        >

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700 sm:w-auto"
            >
                Delete
            </button>

        </form>


        <a
            href="{{ route('ip-access-rules.edit', $ipAccessRule) }}"
            class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
        >
            Edit IP Rule
        </a>

    </div>

</div>

@endsection