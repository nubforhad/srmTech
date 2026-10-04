@extends('admin.layouts.app')

@section('title', 'Edit IP Access Rule')

@section('content')

<div
    class="mx-auto max-w-7xl space-y-6"
>

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Edit IP Access Rule
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Update IP access, categories and services.
            </p>
        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('ip-access-rules.show', $ipAccessRule) }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                View
            </a>

            <a
                href="{{ route('ip-access-rules.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- Errors --}}
    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="font-semibold text-red-700">
                Please fix the following errors:
            </div>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('ip-access-rules.update', $ipAccessRule) }}"
        class="space-y-6"
    >

        @csrf
        @method('PUT')


        {{-- Basic Information --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-lg font-semibold text-slate-800">
                    Basic Information
                </h2>

            </div>


            <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                {{-- Name --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $ipAccessRule->name) }}"
                        placeholder="Example: Office IP Access"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- IP --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Public IP Address
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="ip_address"
                        value="{{ old('ip_address', $ipAccessRule->ip_address) }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 font-mono text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- Title --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $ipAccessRule->title) }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- Subtitle --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Subtitle
                    </label>

                    <input
                        type="text"
                        name="subtitle"
                        value="{{ old('subtitle', $ipAccessRule->subtitle) }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>

            </div>

        </div>


        {{-- Content --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-lg font-semibold text-slate-800">
                    Content Information
                </h2>

            </div>


            <div class="space-y-5 p-5">

                {{-- Paragraph --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Paragraph
                    </label>

                    <textarea
                        name="paragraph"
                        rows="4"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >{{ old('paragraph', $ipAccessRule->paragraph) }}</textarea>

                </div>


                {{-- Details --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Details
                    </label>

                    <textarea
                        name="details"
                        rows="6"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >{{ old('details', $ipAccessRule->details) }}</textarea>

                </div>

            </div>

        </div>


        {{-- Categories & Services --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-lg font-semibold text-slate-800">
                    Categories & Services
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Only active categories and active services are available.
                </p>

            </div>


            <div class="p-5">

                @php
                    $selectedCategories = old(
                        'category_ids',
                        $ipAccessRule->category_ids ?? []
                    );

                    $selectedServices = old(
                        'service_ids',
                        $ipAccessRule->service_ids ?? []
                    );
                @endphp


                @if($categories->count())

                    <div class="space-y-4">

                        @foreach($categories as $category)

                            @php
                                $categorySelected = in_array(
                                    $category->id,
                                    $selectedCategories
                                );
                            @endphp

                            <div
                                class="overflow-hidden rounded-xl border border-slate-200"
                                x-data="{
                                    open: {{ $categorySelected ? 'true' : 'false' }}
                                }"
                            >

                                {{-- Category --}}
                                <div class="flex items-center justify-between gap-3 bg-slate-50 px-4 py-3">

                                    <label class="flex cursor-pointer items-center gap-3">

                                        <input
                                            type="checkbox"
                                            name="category_ids[]"
                                            value="{{ $category->id }}"
                                            x-model="open"
                                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                        >

                                        <div>

                                            <div class="font-semibold text-slate-800">
                                                {{ $category->title }}
                                            </div>

                                            @if($category->subtitle)

                                                <div class="mt-0.5 text-xs text-slate-500">
                                                    {{ $category->subtitle }}
                                                </div>

                                            @endif

                                        </div>

                                    </label>


                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">

                                        {{ $category->services->count() }}

                                        {{ $category->services->count() == 1 ? 'Service' : 'Services' }}

                                    </span>

                                </div>


                                {{-- Services --}}
                                <div
                                    x-show="open"
                                    x-transition
                                    class="border-t border-slate-200 bg-white p-4"
                                >

                                    @if($category->services->count())

                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">

                                            @foreach($category->services as $service)

                                                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 p-3 transition hover:border-blue-300 hover:bg-blue-50">

                                                    <input
                                                        type="checkbox"
                                                        name="service_ids[]"
                                                        value="{{ $service->id }}"
                                                        {{ in_array($service->id, $selectedServices) ? 'checked' : '' }}
                                                        class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                                    >

                                                    <div class="min-w-0">

                                                        <div class="text-sm font-semibold text-slate-700">
                                                            {{ $service->title }}
                                                        </div>

                                                        @if($service->sub_title)

                                                            <div class="mt-1 text-xs text-slate-500">
                                                                {{ $service->sub_title }}
                                                            </div>

                                                        @endif

                                                    </div>

                                                </label>

                                            @endforeach

                                        </div>

                                    @else

                                        <div class="rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-500">
                                            No active services available.
                                        </div>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-700">
                        No active categories available.
                    </div>

                @endif

            </div>

        </div>


        {{-- Access Settings --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-lg font-semibold text-slate-800">
                    Access Settings
                </h2>

            </div>


            <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                {{-- Status --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                        <option
                            value="1"
                            {{ old('status', $ipAccessRule->status ? '1' : '0') == '1' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="0"
                            {{ old('status', $ipAccessRule->status ? '1' : '0') == '0' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>

                </div>


                {{-- Expiry --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Expiry Date
                    </label>

                    <input
                        type="datetime-local"
                        name="expires_at"
                        value="{{ old(
                            'expires_at',
                            $ipAccessRule->expires_at
                                ? $ipAccessRule->expires_at->format('Y-m-d\TH:i')
                                : ''
                        ) }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>

            </div>

        </div>


        {{-- Remark --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="p-5">

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Remark
                </label>

                <textarea
                    name="remark"
                    rows="4"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >{{ old('remark', $ipAccessRule->remark) }}</textarea>

            </div>

        </div>


        {{-- Buttons --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a
                href="{{ route('ip-access-rules.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Update IP Access Rule
            </button>

        </div>

    </form>

</div>

@endsection