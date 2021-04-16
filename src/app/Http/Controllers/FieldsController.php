<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;

class FieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = Field::where("deleted", 0)
            ->orderBy("order")
            ->orderBy("required")
            ->get();
        return view("fields.index", compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view("fields.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreFieldRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFieldRequest $request)
    {
        $field = $request->validated();
        $field["html_data_type"] = Field::getHtmlDataTypeFromForm($field["database_type"]);
        $field["UI_type"] = Field::getUITypeFromForm($field["database_type"]);
        $field["validation_laravel"] = Field::getValidationLaravelFromForm($field);

        $field = Field::create($field);
        if($field->exists){
            //TODO : generate a new migration for refugee table
            //TODO : generate a new json file
        }else{
            //DROP error ?
        }
        return redirect()->route("fields.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        $field = Field::find($id);
        //die(var_dump($field));
        $display_elements = [
            "title"=>"Title",
            "label"=>"Label",
            "placeholder"=>"Placeholder",
            "html_data_type"=>"Html type",
            "UI_type"=>"Java type",
            "linked_list"=>"Associate list",
            "required"=>"Requirement state",
            "status"=>"Status",
            "attribute"=>"Attribute",
            "database_type"=>"Database type",
            "order" => "Order",
            "validation_laravel" => "Validations attributes"];
        return view("fields.show",compact('field', 'display_elements'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $field = Field::find($id);
        return view("fields.edit", compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFieldRequest $request, $id)
    {
        $field = Field::find($id);
        $field->update($request->validate());

        return redirect()->route("fields.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(String $id)
    {
        Field::find($id)
        ->update(["deleted"=>1]);
        return redirect()->route("fields.index");
    }
}
