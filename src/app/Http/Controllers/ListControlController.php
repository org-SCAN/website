<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListControlAddDisplayedValue;
use App\Http\Requests\StoreListControlFieldsRequest;
use App\Http\Requests\StoreListControlRequest;
use App\Http\Requests\StoreUpdateListRequest;
use App\Http\Requests\UpdateListControlRequest;
use App\Http\Requests\UpdateListElemRequest;
use App\Models\Field;
use App\Models\FieldRefugee;
use App\Models\ListControl;
use App\Models\Translation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ListControlController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(ListControl::class, 'lists_control');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $lists = ListControl::whereNotNull("displayed_value");
        if(!env("APP_DEBUG")){
            $lists = $lists->whereVisible(true);
        }
        $lists = $lists->get();

        return view("lists_control.index", compact("lists"));
    }

    /**
     * This function is used to add an element to an existing list
     *
     * @param ListControl $list_control
     * @return View
     *
     */
    public function addToList(ListControl $list_control)
    {
        $this->authorize('addToList', $list_control);
        $list_fields = $list_control->structure;

        return view("lists_control.add_to_list", compact("list_control", 'list_fields'));
    }

    /**
     * This function is used to add an element to a list
     *
     * @param StoreUpdateListRequest $request
     * @param ListControl $listControl
     * @return View
     */
    public function updateList(StoreUpdateListRequest $request, ListControl $listControl)
    {
        $this->authorize('addToList', $listControl);

        $model = 'App\Models\\' . $listControl->name;
        $listElem = $model::create($request->validated());

        Translation::handleTranslation($listControl, $listElem->{$listControl->key_value}, $listElem->{$listControl->displayed_value});
        // I have to translate and add to default langage
        return redirect()->route('lists_control.show', $listControl);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view("lists_control.create");
    }

    /**
     * This function is used to add an element to a list
     *
     * @param ListControl $listControl
     * @param $element
     * @return View
     */
    public function editListElem(ListControl $listControl, $element)
    {
        $this->authorize('updateListElem', $listControl);

        $model = 'App\Models\\' . $listControl->name;
        $list_fields = $listControl->structure;
        $content = $model::find($element);

        return view("lists_control.update_list_element", compact("listControl", "list_fields", 'content'));
    }

    /**
     * This function is used to add an element to a list
     *
     * @param UpdateListElemRequest $request
     * @param ListControl $listControl
     * @param $element
     * @return View
     */
    public function updateListElem(UpdateListElemRequest $request, ListControl $listControl, $element)
    {
        $this->authorize('updateListElem', $listControl);

        $model = 'App\Models\\' . $listControl->name;
        $listElem = $model::find($element);
        $listElem->update($request->validated());
        Translation::handleTranslation($listControl, $listElem->{$listControl->key_value}, $listElem->{$listControl->displayed_value});


        // I have to translate and add to default langage

        return redirect()->route('lists_control.show', $listControl);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateListControlRequest $request
     * @param  ListControl  $lists_control
     * @return RedirectResponse
     */
    public function update(UpdateListControlRequest $request, ListControl $lists_control)
    {
        $lists_control->update($request->validated());
        return redirect()->route("lists_control.index");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreListControlRequest  $request
     * @return View
     */
    public function store(StoreListControlRequest $request)
    {
        $list_control = $request->validated();
        $list_control["name"] = Str::ucfirst(Str::camel("list_" . Str::singular($list_control["title"])));
        // can't add the name to the request before, so validation should be done there.

        $v = Validator::make($list_control, ["name" => "required|unique:list_controls,name"]);
        if ($v->fails()) {
            return redirect()->back()->withErrors(['name' => $v->errors()]);
        }

        $listControl = ListControl::create($list_control);
        return redirect()->route("lists_control.create_fields", $listControl);//view("lists_control.create_fields", compact("listControl"));
    }

    public function createFields(ListControl $listControl)
    {
        $this->authorize('create', $listControl);
        return view("lists_control.create_fields", compact("listControl"));
    }

    /**
     * After creating the list, this controller method is called to store the fields which describes the list
     *
     * @param  StoreListControlFieldsRequest  $request
     * @return RedirectResponse
     */
    public function storeFields(StoreListControlFieldsRequest $request, ListControl $listControl)
    {
        $this->authorize('create', $listControl);
        //store the fields onto the ListStructure table
        foreach($request->validated()['fields'] as $field){
            if(!empty($field) && ($listControl->structure->first() == null || !$listControl->structure()->where('field', $field['name'])->exists())){
                $listControl->structure()->create([
                    "field" => Str::snake($field['name']),
                    "data_type_id" => $field['data_type_id'],
                    "required" => $field['required'] ?? 0,
                ]);
            }
        }

        Artisan::call("make:list", ["id"=>$listControl->id]);
        Artisan::call("migrate");

        $listControl->refresh();
        $fields = $listControl->structure;
        return view("lists_control.define_displayed_value", compact("listControl", "fields"));

    }

    /**
     * Store the displayed value
     *
     * @param  StoreListControlAddDisplayedValue  $request
     * @return RedirectResponse
     */
    public function storeDisplayedValue(StoreListControlAddDisplayedValue $request, ListControl $listControl)
    {
        $this->authorize('create', $listControl);

        $listControl->update($request->validated());
        return redirect()->route("lists_control.index");
    }

    /**
     * Display the specified resource.
     *
     * @param ListControl $lists_control
     * @return View
     */
    public function show(ListControl $lists_control)
    {
        $list_content = $lists_control->getListContent();
        $list_structure = $lists_control->structure;
        return view("lists_control.show", compact("lists_control", "list_structure","list_content"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ListControl $lists_control
     * @return View
     */
    public function edit(ListControl $lists_control)
    {
        return view("lists_control.edit", compact("lists_control"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ListControl $lists_control
     * @return RedirectResponse
     */
    public function destroy(ListControl $lists_control)
    {
        if(Field::whereLinkedList($lists_control->id)->exists()){
            return Redirect::back()->withErrors(["deleteList" => __('lists_control/controller.delete_list_error')]);
        }
        $lists_control->delete();
        return redirect()->route("lists_control.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ListControl $lists_control
     * @param $element
     * @return RedirectResponse
     */
    public function destroyListElem(ListControl $listControl, $element)
    {
        $this->authorize('deleteListElem', $listControl);

        //cheks if the element is used in any field
        if (FieldRefugee::whereValue($element)->exists()) {
            return Redirect::back()->withErrors(["delete." . $element => __('lists_control/controller.delete_element_error')]);
        }
        $model = 'App\Models\\' . $listControl->name;
        $model::find($element)->delete();
        return redirect()->route("lists_control.show", $listControl);
    }

    /**
     * This function returns the lists of all the lists to the API
     * @param Request $request
     * @return JsonResponse
     */
    public function apiGetLists(Request $request) {
        //check that the user is authorized
        if ($request->user()->tokenCan("read")) {
            $lists = ListControl::all();
            return response(json_encode($lists->makeHidden(['created_at', 'updated_at','deleted_at'] )), 200)->header("Content-Type", "application/json");
        }
        else {
            return response("Your token can't be use to get data",
                403);
        }
    }

    /**
     * This function returns the content of a list to the API
     * @param Request $request
     * @param ListControl $listControl
     * @return JsonResponse
     */

    public function apiGetList(Request $request, ListControl $listControl) {
        //check that the user is authorized
        if ($request->user()->tokenCan("read")) {
            $list = $listControl->getListContent();

            // get the Translation of the displayed value
            $translations = Translation::where('list', $listControl->id)
                ->select(['language', 'field_key','translation'])
                ->get();
            foreach($list as $element){
                $element[$listControl->displayed_value] = $translations->where('field_key', $element->{$listControl->key_value})->pluck('translation', 'language');
            }


            return response(json_encode($list->makeHidden([
                'created_at',
                'updated_at',
                'deleted_at'
            ])),
                200)->header("Content-Type",
                "application/json");
        } else {
            return response("Your token can't be use to get data",
                403);
        }
    }
}
