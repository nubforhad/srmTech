<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\IpAccessRule;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IpAccessRuleController extends Controller
{
    /**
     * Display a listing of IP access rules.
     */
    public function index(Request $request)
    {
        $query = IpAccessRule::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $ipAccessRules = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.ip-access-rules.index',
            compact('ipAccessRules')
        );
    }


    /**
     * Show the form for creating a new IP access rule.
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Only Active Categories
        |--------------------------------------------------------------------------
        */
        $categories = Category::where('status', 1)
            ->with([
                'services' => function ($query) {
                    $query->where('status', 1)
                        ->orderBy('title');
                }
            ])
            ->orderBy('title')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Only Active Services
        |--------------------------------------------------------------------------
        */
        $services = Service::where('status', 1)
            ->with('category')
            ->orderBy('title')
            ->get();

        return view(
            'admin.ip-access-rules.create',
            compact(
                'categories',
                'services'
            )
        );
    }


    /**
     * Store a newly created IP access rule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'subtitle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'paragraph' => [
                'nullable',
                'string',
            ],

            'details' => [
                'nullable',
                'string',
            ],

            'ip_address' => [
                'required',
                'ip',
                'max:45',
                'unique:ip_access_rules,ip_address',
            ],

            /*
            |--------------------------------------------------------------------------
            | Only Active Categories Can Be Assigned
            |--------------------------------------------------------------------------
            */
            'category_ids' => [
                'nullable',
                'array',
            ],

            'category_ids.*' => [
                'integer',
                Rule::exists('categories', 'id')
                    ->where(function ($query) {
                        $query->where('status', 1);
                    }),
            ],

            /*
            |--------------------------------------------------------------------------
            | Only Active Services Can Be Assigned
            |--------------------------------------------------------------------------
            */
            'service_ids' => [
                'nullable',
                'array',
            ],

            'service_ids.*' => [
                'integer',
                Rule::exists('services', 'id')
                    ->where(function ($query) {
                        $query->where('status', 1);
                    }),
            ],

            'remark' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'expires_at' => [
                'nullable',
                'date',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Store Selected IDs
        |--------------------------------------------------------------------------
        */
        $validated['category_ids'] = $request->input(
            'category_ids',
            []
        );

        $validated['service_ids'] = $request->input(
            'service_ids',
            []
        );


        IpAccessRule::create($validated);

        return redirect()
            ->route('ip-access-rules.index')
            ->with(
                'success',
                'IP access rule created successfully.'
            );
    }


    /**
     * Display the specified IP access rule.
     */
    public function show(IpAccessRule $ipAccessRule)
    {
        /*
        |--------------------------------------------------------------------------
        | Assigned Categories
        | Only categories that still exist
        |--------------------------------------------------------------------------
        */
        $categories = Category::whereIn(
            'id',
            $ipAccessRule->category_ids ?? []
        )
            ->orderBy('title')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Assigned Services
        | Only services that still exist
        |--------------------------------------------------------------------------
        */
        $services = Service::with('category')
            ->whereIn(
                'id',
                $ipAccessRule->service_ids ?? []
            )
            ->orderBy('title')
            ->get();

        return view(
            'admin.ip-access-rules.show',
            compact(
                'ipAccessRule',
                'categories',
                'services'
            )
        );
    }


    /**
     * Show the form for editing the specified IP access rule.
     */
    public function edit(IpAccessRule $ipAccessRule)
    {
        /*
        |--------------------------------------------------------------------------
        | Only Active Categories
        |--------------------------------------------------------------------------
        */
        $categories = Category::where('status', 1)
            ->with([
                'services' => function ($query) {
                    $query->where('status', 1)
                        ->orderBy('title');
                }
            ])
            ->orderBy('title')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Only Active Services
        |--------------------------------------------------------------------------
        */
        $services = Service::where('status', 1)
            ->with('category')
            ->orderBy('title')
            ->get();

        return view(
            'admin.ip-access-rules.edit',
            compact(
                'ipAccessRule',
                'categories',
                'services'
            )
        );
    }


    /**
     * Update the specified IP access rule.
     */
    public function update(
        Request $request,
        IpAccessRule $ipAccessRule
    ) {
        $validated = $request->validate([
            'name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'subtitle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'paragraph' => [
                'nullable',
                'string',
            ],

            'details' => [
                'nullable',
                'string',
            ],

            'ip_address' => [
                'required',
                'ip',
                'max:45',
                Rule::unique('ip_access_rules', 'ip_address')
                    ->ignore($ipAccessRule->id),
            ],

            /*
            |--------------------------------------------------------------------------
            | Only Active Categories
            |--------------------------------------------------------------------------
            */
            'category_ids' => [
                'nullable',
                'array',
            ],

            'category_ids.*' => [
                'integer',
                Rule::exists('categories', 'id')
                    ->where(function ($query) {
                        $query->where('status', 1);
                    }),
            ],

            /*
            |--------------------------------------------------------------------------
            | Only Active Services
            |--------------------------------------------------------------------------
            */
            'service_ids' => [
                'nullable',
                'array',
            ],

            'service_ids.*' => [
                'integer',
                Rule::exists('services', 'id')
                    ->where(function ($query) {
                        $query->where('status', 1);
                    }),
            ],

            'remark' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'expires_at' => [
                'nullable',
                'date',
            ],
        ]);


        $validated['category_ids'] = $request->input(
            'category_ids',
            []
        );

        $validated['service_ids'] = $request->input(
            'service_ids',
            []
        );


        $ipAccessRule->update($validated);

        return redirect()
            ->route('ip-access-rules.index')
            ->with(
                'success',
                'IP access rule updated successfully.'
            );
    }


    /**
     * Remove the specified IP access rule.
     */
    public function destroy(IpAccessRule $ipAccessRule)
    {
        $ipAccessRule->delete();

        return redirect()
            ->route('ip-access-rules.index')
            ->with(
                'success',
                'IP access rule deleted successfully.'
            );
    }
}