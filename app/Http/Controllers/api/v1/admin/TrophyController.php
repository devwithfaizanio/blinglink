<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\trophy\addTrophyRequest;
use App\Http\Requests\api\v1\admin\trophy\assignTrophyRequest;
use App\Http\Requests\api\v1\admin\trophy\removeTrophyRequest;
use App\Http\Requests\api\v1\admin\trophy\updateTrophyRequest;
use App\Http\Resources\api\v1\admin\trophy\getTrophyResource;
use App\Models\Trophy;
use App\Models\User;
use App\Services\ImageService;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;


#[Group('Admin · Trophy', weight: 3)]
class TrophyController extends Controller
{
    public function index(Request $request)
    {
        $trophies = Trophy::latest()->get();
        return $this->success(message: 'success', data: getTrophyResource::collection($trophies) );
    }

    public function store(addTrophyRequest $request)
    {

        $image = null;
        if ($request->hasFile('image')) {
            $image = ImageService::addImage('images/trophies', $request->file('image'), 'trophy_');
        }

        $trophy = Trophy::create([
            'name'        => $request->name,
            'image'       => $image,
            'description' => $request->description,
        ]);

        return $this->success( message: 'success', data: null);
    }

    public function update(updateTrophyRequest $request)
    {
        $trophyId = $request->trophy_id;
        $trophy = Trophy::findOrFail($trophyId);


        if ($request->hasFile('image')) {
            $trophy->image = ImageService::updateImage('images/trophies/',$request->image, $trophy->image, 'trophy_');
        }

        $trophy->name        = $request->input('name', $trophy->name);
        $trophy->description = $request->input('description', $trophy->description);

        $trophy->save();

        return $this->success(message: 'success', data: null);
    }

    public function assignTrophy(assignTrophyRequest $request)
    {
        $user = User::findOrFail($request->user_id);

        $user->trophy_id = $request->trophy_id;
        $user->trophy_reward = $request->reward;
        $user->save();

        return $this->success(message: 'success');
    }

    public function removeTrophy(removeTrophyRequest $request)
    {

        $user = User::findOrFail($request->user_id);

        $user->trophy_id = null;
        $user->trophy_reward = null;
        $user->save();

        return $this->success(message: 'success');
    }
}
