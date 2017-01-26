<?php

namespace App\Api\V1\Controllers;

use App\Routines;
use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoutinesController extends Controller
{
    use Helpers;

    public function __construct()
    {

    }

    public function index()
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        return $currentUser
            ->routines()
            ->orderBy('created', 'DESC')
            ->get()
            ->toArray();
    }

    public function store(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $routine = new Routines();
        $routine->user_id = $currentUser->id;
        $routine->monday = $request->get('monday');
        $routine->tuesday = $request->get('tuesday');
        $routine->wednesday = $request->get('wednesday');
        $routine->thursday = $request->get('thursday');
        $routine->friday = $request->get('friday');
        $routine->saturday = $request->get('saturday');
        $routine->sunday = $request->get('sunday');

        if ($currentUser->routines()->save($routine)) {
            return $this->response->created('Routine has been successfully created.');
        } else {
            return $this->response->error('could_not_create_routine', 500);
        }
    }

    public function show($id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $routine = $currentUser->routines()->find($id);

        if (!$routine) {
            throw new NotFoundHttpException;
        }

        return $routine;
    }

    public function update(Request $request, $id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $routine = $currentUser->routines()->find($id);
        if (!$routine) {
            throw new NotFoundHttpException;
        }

        $routine->fill($request->all());

        if ($routine->save()) {
            return $this->response()->noContent();
        } else {
            return $this->response->error('could_not_update_routine', 500);
        }
    }

    public function delete($id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $routine = $currentUser->routines()->find($id);

        if (!$routine) {
            throw new NotFoundHttpException;
        }

        if ($routine->delete()) {
            return $this->response->noContent();
        } else {
            return $this->response->error('could_not_delete_routine', 500);
        }
    }
}
