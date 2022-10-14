<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListControlRequest;
use App\Http\Requests\StoreUpdateListRequest;
use App\Http\Requests\UpdateListControlRequest;
use App\Http\Requests\StoreListControlFieldsRequest;
use App\Http\Requests\StoreListControlAddDisplayedValue;
use App\Http\Requests\UpdateListElemRequest;
use App\Models\FieldRefugee;
use App\Models\ListControl;
use App\Models\Translation;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use App\Models\Field;
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
        $lists = ListControl::whereNotNull("displayed_value")->get();
        return view("lists_control.index", compact("lists"));
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
     * This function is used to add an element to an existing list
     *
     * @param ListControl $list_control
     * @return View
     *
     */
    public function addToList(ListControl $list_control)
    {

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
        $model = 'App\Models\\' . $listControl->name;
        $listElem = $model::create($request->validated());
        Translation::create([
            "language" => Language::where('default', 1)->first()->id,
            "list" => $listControl->id,
            'field_key' => $listElem->{$listControl->key_value},
            'translation' => $listElem->{$listControl->displayed_value},

        ]);
        // I have to translate and add to default langage
        return redirect()->route('lists_control.show', $listControl);
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
        $model = 'App\Models\\' . $listControl->name;
        $list_fields = $listControl->structure;
        $content = $model::find($element);

        return view("lists_control.update_list_element", compact("listControl","list_fields", 'content'));
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
        $model = 'App\Models\\' . $listControl->name;
        $listElem = $model::find($element);
        $listElem->update($request->validated());
        Translation::whereLanguage(Language::firstWhere('default', 1)->id)
            ->whereList($listControl->id)
            ->firstWhere('field_key',$listElem->{$listControl->key_value})
            ->update(['translation' => $listElem->{$listControl->displayed_value}]);

        // I have to translate and add to default langage

        return redirect()->route('lists_control.show', $listControl);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreListControlRequest $request
     * @return View
     */
    public function store(StoreListControlRequest $request)
    {
        $list_control = $request->validated();
        $list_control["name"] = Str::ucfirst(Str::camel("list_".Str::singular($list_control["title"])));
        // can't add the name to the request before, so validation should be done there.

        $v = Validator::make($list_control, ["name" => "required|unique:list_controls,name"]);
        if ($v->fails()) {
            return redirect()->back()->withErrors(['name' => $v->errors()]);
        }

        $listControl = ListControl::create($list_control);
        return view("lists_control.create_fields", compact("listControl"));
    }

    /**
     * After creating the list, this controller method is called to store the fields which describes the list
     *
     * @param \App\Http\Requests\StoreListControlFieldsRequest $request
     * @return RedirectResponse
     */
    public function storeFields(StoreListControlFieldsRequest $request, ListControl $listControl)
    {

        //store the fields onto the ListStructure table
        foreach($request->validated()['fields'] as $field){
            if(!empty($field) && ($listControl->structure->first() == null || !$listControl->structure()->firstWhere('field', $field)->exists())){
                $listControl->structure()->create([
                    "field" => $field
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
     * @param \App\Http\Requests\StoreListControlAddDisplayedValue $request
     * @return RedirectResponse
     */
    public function storeDisplayedValue(StoreListControlAddDisplayedValue $request, ListControl $listControl){
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
     * Update the specified resource in storage.
     *
     * @param UpdateListControlRequest $request
     * @param \App\Models\ListControl $lists_control
     * @return RedirectResponse
     */
    public function update(UpdateListControlRequest $request, ListControl $lists_control)
    {
        $lists_control->update($request->validated());
        return redirect()->route("lists_control.index");
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
            return Redirect::back()->withErrors(["deleteList" => "You can't delete this element since it's used in at least one field."]);
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
    public function destroyListElem(ListControl $listControl, $element){
        //cheks if the element is used in any field
        if(FieldRefugee::whereValue($element)->exists()){
            return Redirect::back()->withErrors(["delete.".$element => "You can't delete this element since it's used in at least one person."]);
        }
        $model = 'App\Models\\' . $listControl->name;
        $model::find($element)->delete();
        return redirect()->route("lists_control.show", $listControl);
    }
}
