<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Models\ApiLog;
use App\Models\Field;
use App\Models\ListControl;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FieldsController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Field::class, 'field');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view("fields.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view("fields.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFieldRequest $request
     * @return RedirectResponse
     */
    public function store(StoreFieldRequest $request)
    {
        $apiLog = ApiLog::createFromRequest($request, 'Field');
        $field = $request->validated();
        $field["api_log"] = $apiLog->id;
        if (empty($field["order"])) {
            $last_order = Field::where('crew_id', Auth::user()->crew->id)->get()->sortByDesc('order')->first();
            $field["order"] = empty($last_order) ? 1 : $last_order->order + 1;
        }
        $field["html_data_type"] = Field::getHtmlDataTypeFromForm($field["database_type"]);
        $field["android_type"] = Field::getUITypeFromForm($field["database_type"]);
        $field["validation_laravel"] = Field::getValidationLaravelFromForm($field);
        $field["crew_id"] = Auth::user()->crew->id;

        $field = Field::create($field);
        $apiLog->response = "success";
        $apiLog->save();
        return redirect()->route("fields.index");
    }

    /**
     * Display the specified resource.
     *
     * @param Field $field
     * @return View
     */
    public function show(Field $field)
    {
        $display_elements = [
            "title" => "Title",
            "label" => "Label",
            "placeholder" => "Placeholder",
            "html_data_type" => "Html type",
            "android_type" => "Java type",
            // "linked_list"=>"Associate list", is removed because it needs a particular display
            "required" => "Requirement state",
            "status" => "Status",
            "attribute" => "Attribute",
            "database_type" => "Database type",
            "order" => "Order",
            "validation_laravel" => "Validations attributes",
            "descriptive_value" => "Descriptive value",
            "best_descriptive_value" => "Best descriptive value"
        ];
        return view("fields.show",compact('field', 'display_elements'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Field $field
     * @return View
     */
    public function edit(Field $field)
    {
        $linked_list_id = $field->getLinkedListId();
        $lists["database_type"] = array("string" => "Small text", "text" => "Long text", "integer" => "Number", "date" => "Date", "boolean" => "Yes / No ");
        $lists["database_type"] = [$field->database_type => $lists["database_type"][$field->database_type]] + $lists["database_type"];

        $lists["required"] = array(0 => "Auto generated", 2 => "Strongly advised", 3 => "Advised", 4 => "If possible", 100 => "Undefined");
        if ($field->getRequiredId() != 1) { // 1 is for required
            $lists["required"] = [$field->getRequiredId() => $lists["required"][$field->getRequiredId()]] + $lists["required"];
        }

        $lists["status"] = array(0 => "Disabled", 1 => "Website", 2 => "Website & App");
        $lists["status"] = [$field->getStatusId() => $lists["status"][$field->getStatusId()]] + $lists["status"];

        if (empty($linked_list_id)) {
            $lists["linked_list"] = [" " => "Choose an associate list"];
        } else {
            $lists["linked_list"] = array_column(ListControl::where('id', $linked_list_id)->get()->toArray(), "title", "id");
        }
        $lists["linked_list"] += array_column(ListControl::where("id", "!=", $linked_list_id)->get()->toArray(), "title", "id");
        return view("fields.edit", compact('field', "lists"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFieldRequest $request
     * @param Field $field
     * @return RedirectResponse
     */
    public function update(UpdateFieldRequest $request, Field $field)
    {
        $apiLog = ApiLog::createFromRequest($request, 'Field');
        $to_update = $request->validated();
        if (!$request->has('descriptive_value')) {
            $to_update['descriptive_value'] = 0;
        }
        if (!$request->has('best_descriptive_value')) {
            $to_update['best_descriptive_value'] = 0;
        }
        $to_update["database_type"] = $field->database_type;
        $to_update["api_log"] = $apiLog->id;
        $to_update["validation_laravel"] = Field::getValidationLaravelFromForm($to_update);
        $field->update($to_update);
        $apiLog->response = "success";
        $apiLog->save();
        return redirect()->route("fields.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Field $field
     * @return RedirectResponse
     */
    public function destroy(Field $field)
    {
        $field->delete();
        return redirect()->route("fields.index");
    }

    /**
     * Returns a json which contains all the fields
     *
     * @param Request $request
     */
    public function handleApiRequest(Request $request)
    {
        $log = ApiLog::createFromRequest($request, "Refugee");
        if ($request->user()->tokenCan("read")) {
            $datas = array();
            foreach (ListControl::whereRelation('crews', 'crew_id', $request->user()->crew->id)->get() as $list) {
                $call_class = '\App\Models\\' . $list->name;
                $datas[$list->name] = $call_class::getAPIContent($request->user());
            }
            return response(json_encode($datas), 200)->header('Content-Type', 'application/json');
        }
        $log->update(["response"=>"Bad token access"]);
        return response("Your token can't be use to read datas", 403);

    }
}
