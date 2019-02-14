<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['init', 'login']);
    }

    public function init()
    {
        $user = Auth::guard('admin')->user();

        return response()->json([
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password]))
        {
            return response()->json(Auth::guard('admin')->user(), 200);
        }
        else
        {
            return response()->json(['error' => 'Could not log you in.'], 401);
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return response()->json(true, 200);
    }

    public function resourceIndex(Request $request)
    {
        $model = $request->model;

        $resources = $model::paginate(10);

        return response()->json($resources, 200);
    }

    public function findResourceById(Request $request)
    {
        $model = $request->model;
        $resource = $model::query();

        if (count($request->with))
        {
            foreach ($request->with as $with)
            {
                $resource = $resource->with($with['relationship']);
            }
        }

        $resource = $resource->where('id', $request->resource_id)->first();



        return response()->json($resource, 200);

    }

    public function attachRelationship(Request $request)
    {
        $model = $request->model;
        $resource = $model::where('id', $request->resource_id)->first();
        $currentWith = $request->current_with;

        if ($currentWith['type'] === 'belongsTo')
        {
            $resource->{$request->with}()->associate($request->attached_resource_id);
            $resource->save();
        }
        elseif ($currentWith['type'] === 'hasMany')
        {
            $attachedResourceModel = $currentWith['model'];
            $attachedResource = $attachedResourceModel::find($request->attached_resource_id);
            $attachedResource->{$currentWith['foreignKey']} = $request->resource_id;
            $attachedResource->save();
        }




        return response()->json(true, 200);
    }

    public function detachRelationship(Request $request)
    {
        $model = $request->model;
        $resource = $model::where('id', $request->resource_id)->first();

        $resource->{$request->with}()->dissociate($request->attached_resource_id);
        $resource->save();

        return response()->json(true, 200);
    }

    public function getRelationshipResources(Request $request)
    {
        $model = $request->model;
        $resources = $model::paginate(10);

        return response()->json($resources, 200);
    }

    public function createResource(Request $request)
    {
        $model = $request->model;
        $resource = new $model();

        foreach ($request->resource['fields'] as $field)
        {
            if (!$field['auto'])
            {
                if (isset($field['bcrypt']) && $field['bcrypt'] === true)
                {
                    $resource->{$field['column']} = bcrypt($field['model']);
                }
                else
                {
                    $resource->{$field['column']} = $field['model'];
                }
            }
        }

        $resource->save();

        return response()->json($resource, 200);
    }

    public function deleteResource(Request $request)
    {
        $model = $request->model;

        $resource = $model::where('id', $request->resource_id)->first();
        $resource->delete();
    }

    public function updateResource(Request $request)
    {
        $model = $request->model;
        $resource = $model::where('id', $request->resource_id)->first();

        foreach ($request->fields as $field)
        {
            if (isset($field['bcrypt']) && $field['bcrypt'] === true)
            {
                if (isset($field['model']))
                {
                    $resource->{$field['column']} = bcrypt($field['model']);
                }
            }
            else
            {
                if (isset($field['model']))
                {
                    $resource->{$field['column']} = $field['model'];

                }
            }
        }

        $resource->save();

        return response()->json($resource, 200);

    }
}
