<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Models\ApiLog;
use App\Models\Field;
use App\Models\ListControl;
use App\Models\ListDataType;
use App\Models\ListRelation;
use App\Models\Translation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FieldsController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->authorizeResource(Field::class,
            'field');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index() {
        return view("fields.index");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreFieldRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreFieldRequest $request) {
        $apiLog = ApiLog::createFromRequest($request,'Field');
        $field = $request->validated();
        $field["api_log"] = $apiLog->id;


        // compute the order
        if (empty($field["order"])) {
            $last_order = Field::where('crew_id',
                Auth::user()->crew->id)->get()->sortByDesc('order')->first();
            $field["order"] = empty($last_order) ? 1 : $last_order->order + 1;
        }

        // compute the label
        $field["label"] = Str::snake($field["title"]);

        // if required is not set to 1, add nullable to the validation rules
        if ($field["required"] != 1) {
            $field["validation_rules"] .= "|nullable";
        }
        else{
            $field["validation_rules"] .= "|required";
        }

        $field["validation_laravel"] = implode("|",
            array_merge(
                explode("|",ListDataType::find($field["data_type_id"])->validation),
                explode("|", $field['validation_rules'])
            )
        );
        unset($field["validation_rules"]);
        $field["crew_id"] = Auth::user()->crew->id;

        $field = Field::create($field);


        $listField = ListControl::firstWhere('name',
            'Field');

        //translate the field
        Translation::handleTranslation($listField,
            $field->{$listField->key_value},
            $field->{$listField->displayed_value});


        $apiLog->response = "success";
        $apiLog->save();
        return redirect()->route("fields.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create() {
        $data_types = ListDataType::list();
        return view("fields.create", compact('data_types'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Field  $field
     * @return View
     */
    public function show(Field $field) {
        $display_elements = [
            "Title" => $field->title,
            "Placeholder" => $field->placeholder,
            "Requirement state" => $field->required,
            "Status" => $field->status,
            "Order" => $field->order,
            "Validation rules" => $field->validation_laravel,
            "Data type" => $field->dataType->displayedValueContent,
            "Descriptive value" => $field->descriptive_value ? "Yes" : "No",
            "Best descriptive value" => $field->best_descriptive_value ? "Yes" : "No",
        ];
        return view("fields.show",
            compact('field',
                'display_elements'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Field  $field
     * @return View
     */
    public function edit(Field $field) {
        $linked_list_id = $field->getLinkedListId();
        $lists["database_type"] = ListDataType::list();
        //$lists["database_type"] = [$field->database_type => $lists["database_type"][$field->database_type]] + $lists["database_type"];

        $lists["required"] = [
            0 => "Auto generated",
            2 => "Strongly advised",
            3 => "Advised",
            4 => "If possible",
            100 => "Undefined",
        ];
        if ($field->getRequiredId() != 1) { // 1 is for required
            $lists["required"] = [$field->getRequiredId() => $lists["required"][$field->getRequiredId()]] + $lists["required"];
        }

        $lists["status"] = Field::$statusTypes;
        $lists["status"] = [$field->getStatusId() => $lists["status"][$field->getStatusId()]] + $lists["status"];

        if (empty($linked_list_id)) {
            $lists["linked_list"] = [" " => "Choose an associate list"];
        } else {
            $lists["linked_list"] = array_column(ListControl::where('id',
                $linked_list_id)->get()->toArray(),
                "title",
                "id");
        }
        $lists["linked_list"] += array_column(ListControl::where("id",
            "!=",
            $linked_list_id)->get()->toArray(),
            "title", "id");
        return view("fields.edit",
            compact('field',
                "lists"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Field  $field
     * @return RedirectResponse
     */
    public function destroy(Field $field) {
        $field->delete();
        return redirect()->route("fields.index");
    }

    /**
     * Returns a json which contains all the fields
     *
     * @param  Request  $request
     */
    public function handleApiRequest(Request $request) {
        $log = ApiLog::createFromRequest($request,
            "Refugee");
        if ($request->user()->tokenCan("read")) {
            $datas = [];
            $crew = $request->user()->crew;
            foreach (ListControl::whereRelation('crews',
                'crew_id',
                $crew->id)->get() as $list) {
                //chek if the associated field should be present in the app
                $fields = Field::where('crew_id',
                    $crew->id)->where('linked_list',
                    $list->id)->where('status',
                    '>=',
                    2)->get();
                if ($fields->count() > 0) {
                    $call_class = '\App\Models\\'.$list->name;
                    $datas[$list->id] = $call_class::getAPIContent($request->user());
                }
            }
            $datas['fields'] = Field::getAPIContent($request->user());
            $datas['ListRelations'] = ListRelation::getAPIContent($request->user());
            return response(json_encode($datas),
                200)->header('Content-Type',
                'application/json');
        }
        $log->update(["response" => "Bad token access"]);
        return response("Your token can't be use to read datas",
            403);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateFieldRequest  $request
     * @param  Field  $field
     * @return RedirectResponse
     */
    public function update(UpdateFieldRequest $request, Field $field) {
        $apiLog = ApiLog::createFromRequest($request,
            'Field');

        $to_update = $request->validated();
        if (!$request->has('descriptive_value')) {
            $to_update['descriptive_value'] = 0;
        }
        if (!$request->has('best_descriptive_value')) {
            $to_update['best_descriptive_value'] = 0;
        }

        $to_update["api_log"] = $apiLog->id;
        $to_update["validation_laravel"] = $to_update["validation_rules"];
        unset($to_update["validation_rules"]);
        $field->update($to_update);

        $listField = ListControl::firstWhere('name',
            'Field');
        Translation::handleTranslation($listField,
            $field->{$listField->key_value},
            $field->{$listField->displayed_value});

        $apiLog->response = "success";
        $apiLog->save();
        return redirect()->route("fields.index");
    }
}
