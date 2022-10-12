<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListControlRequest;
use App\Http\Requests\StoreUpdateListRequest;
use App\Http\Requests\UpdateListControlRequest;
use App\Models\ListControl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
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
        $lists = ListControl::all();
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

        $list_fields = $list_control->getListFields();

        if ($list_control->key_value == "id") {
            unset($list_fields[array_keys($list_fields, "key")[0]]);
        }

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
        ddd($request->validated());
        $model::create($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreListControlRequest $request
     * @return View
     */
    public function store(StoreListControlRequest $request)
    {
        // TODO : créer la migration
        // TODO : créer le modèle

        $list_control = $request->validated();
        $list_control["name"] = "List".Str::camel($list_control["title"]);


        ListControl::create($request->validated());
        return redirect()->route("lists_control.create_fields");
    }

    /**
     * After creating the list, this controller method is called to store the fields which describes the list
     *
     * @param \App\Http\Requests\StoreListControlRequest $request
     * @return RedirectResponse
     */
    public function storeFields(StoreListControlFieldsRequest $request, ListControl $listControl)
    {

        foreach($request->validated() as $field){
            $listControl->structure()->create([
                "field" => $field
            ]); // todo verif qu'il me prend bien le $listControl->id
        }

        Artisan::call("make:list", ["id"=>$listControl->id]);
        //store the fields onto the ListStructure table


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
        $list_content = $lists_control->getListContent()->toArray();
        return view("lists_control.show", compact("lists_control", "list_content"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ListControl $lists_control
     * @return View
     */
    public function edit(ListControl $lists_control)
    {
        //$fields = $lists_control
        return view("lists_control.edit", compact("lists_control"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateListControlRequest $request
     * @param \App\Models\ListControl $listControl
     * @return RedirectResponse
     */
    public function update(UpdateListControlRequest $request, ListControl $listControl)
    {
        $listControl->update($request->validated());
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
        $lists_control->delete();
        return redirect()->route("lists_control.index");
    }
}
