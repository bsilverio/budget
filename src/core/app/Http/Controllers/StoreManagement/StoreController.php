<?php

namespace App\Http\Controllers\StoreManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\RobotController;
use App\Models\Store;
use App\Models\Robot;
use Illuminate\Validation\Rule;
use Validator;
use Config;
use Illuminate\Http\Response as HttpResponse;

class StoreController extends RobotController
{
    public function execute(Request $request, $id) {
        $store = Store::find($id);
        $store->load('robots');
        if($store) {
            $result = $store->execute();

            if($result['success']) {
                $response = $store->toArray();
                return response()->json($response, HttpResponse::HTTP_OK);
            } else {
                return response()->json($result, HttpResponse::HTTP_BAD_REQUEST);
            }

        } else {
            $response = ['success' => 0, 'errors' => [trans('request.store.store_not_found')]];
            return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function create(Request $request)
    {
        $input = $request->only('width', 'height');

        $validator = Validator::make($input, Store::$rules, trans("validation_messages.store_validation"));

        if ($validator->fails()) {
            foreach ($validator->errors()->keys() as $field) {
                $errors[$field] = $validator->errors()->get($field);
            }

            $response = [
                'success' => 0,
                'errors' => $errors
            ];

            return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            Store::unguard();
            $store = Store::create($input);
            Store::reguard();

            $store->load('robots');

            $response = $store->toArray();

            return response()->json($response, HttpResponse::HTTP_OK);
        }
    }

    public function get(Request $request, $id)
    {
        $store = Store::find($id);

        if ($store) {
            $store->load('robots');
            return response()->json($store->toArray(), HttpResponse::HTTP_OK);
        } else {
            $response = ['success' => 0, 'errors' => [trans('request.store.store_not_found')]];
            return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function delete(Request $request, $id) {
        $store = Store::find($id);

        if($store) {
            Store::where('id',$id)->delete();
            return response(HttpResponse::HTTP_OK);
        } else {
            $response = ['success' => 0, 'errors' => [trans('request.store.store_not_found')]];
            return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function create_robot(Request $request, $id)
    {
        $store = Store::find($id);

        if ($store) {
            $input = $request->only('x', 'y', 'heading', 'commands');
            $input['store_id'] = $id;

            $validator = Validator::make($input, Robot::$rules, trans("validation_messages.robot_validation"));

            if ($validator->fails()) {
                $errors = [];
                foreach ($validator->errors()->keys() as $field) {
                    $errors[$field] = $validator->errors()->get($field);
                }

                $result = Robot::customValidateInput($input, $store, $errors);

                if (!$result['success']) {
                    $errors = $result['errors'];
                }

                $response = [
                    'success' => 0,
                    'errors' => $errors
                ];

                return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $errors = [];
                $result = Robot::customValidateInput($input, $store, $errors);

                if (!$result['success']) {
                    $errors = $result['errors'];

                    $response = [
                        'success' => 0,
                        'errors' => $errors
                    ];

                    return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    Robot::unguard();
                    $robot = new Robot($input);
                    $store->robots()->save($robot);
                    Robot::reguard();
                    return response()->json($robot->toArray(), HttpResponse::HTTP_OK);
                }
            }
        } else {
            $response = ['success' => 0, 'errors' => [trans('request.store.store_not_found')]];
            return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function update_robot(Request $request, $id, $rid)
    {
        $store = Store::find($id);

        if ($store) {
            $robot = Robot::find($rid);
            if ($robot) {
                if($robot->store_id != $id) {
                    $response = ['success' => 0, 'errors' => [trans('request.robot.robot_not_in_store')]];
                    return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
                }

                $input = $request->intersect(['x', 'y', 'heading', 'commands']);
                $validator = Validator::make($input, Robot::$updateRules, trans("validation_messages.robot_validation"));

                if ($validator->fails()) {
                    $errors = [];
                    foreach ($validator->errors()->keys() as $field) {
                        $errors[$field] = $validator->errors()->get($field);
                    }

                    $result = Robot::customValidateInput($input, $store, $errors);

                    if (!$result['success']) {
                        $errors = $result['errors'];
                    }

                    $response = [
                        'success' => 0,
                        'errors' => $errors
                    ];

                    return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    $errors = [];
                    $result = Robot::customValidateInput($input, $store, $errors);

                    if (!$result['success']) {
                        $errors = $result['errors'];

                        $response = [
                            'success' => 0,
                            'errors' => $errors
                        ];

                        return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
                    } else {
                        Robot::where("id", $rid)->update($input);

                        $robot = Robot::find($rid);

                        return response()->json($robot->toArray(), HttpResponse::HTTP_OK);
                    }
                }
            } else {
                $response = ['success' => 0, 'errors' => [trans('request.robot.robot_not_found')]];
                return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {
            $response = ['success' => 0, 'errors' => [trans('request.store.store_not_found')]];
            return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function delete_robot(Request $request, $id, $rid)
    {
        $store = Store::find($id);
        if ($store) {
            $robot = Robot::find($rid);
            if ($robot) {

                if($robot->store_id != $id) {
                    $response = ['success' => 0, 'errors' => [trans('request.robot.robot_not_in_store')]];
                    return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
                }

                Robot::where("id", $rid)->delete();
                return response(HttpResponse::HTTP_OK);
            } else {
                $response = ['success' => 0, 'errors' => [trans('request.robot.robot_not_found')]];
                return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {
            $response = ['success' => 0, 'errors' => [trans('request.store.store_not_found')]];
            return response()->json($response, HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        }
    }
}
