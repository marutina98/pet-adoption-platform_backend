<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{

    public function index() {
        return Status::all();
    }

    public function show(Status $status) {
        return $status;
    }

    public function store() {
        
        $data = request()->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);

        $data['name'] = strip_tags($data['name']);
        $data['description'] = strip_tags($data['description']);

        $status = Status::create($data);

        return response()->json([
            'message' => 'Status: Creation Successful',
            'object' => $status,
        ], 200);

    }

    public function update(Status $status) {

        $data = request()->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);

        $data['name'] = strip_tags($data['name']);
        $data['description'] = strip_tags($data['description']);

        $status->update($data);

        return response()->json([
            'message' => 'Status: Update Successful',
            'object' => $status,
        ], 200);

    }

    public function delete(Status $status) {

        $status->delete();

        return response()->json([
            'message' => 'Status: Deletion Successful',
        ], 200);

    }

}
