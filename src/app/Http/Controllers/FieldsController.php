<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view("fields.index");
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
        if(empty($field["order"])){
            $field["order"]=100;
        }
        $field["html_data_type"] = Field::getHtmlDataTypeFromForm($field["database_type"]);
        $field["UI_type"] = Field::getUITypeFromForm($field["database_type"]);
        $field["validation_laravel"] = Field::getValidationLaravelFromForm($field);
        $field = Field::create($field);
        if($field->exists){
            $field->addFieldtoRefugees();
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
            // "linked_list"=>"Associate list", is removed because it needs a particular display
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
        $linked_list_id = $field->getLinkedListId();
        $lists["database_type"]= array("string" => "Small text","integer" => "Number","date" => "Date","boolean" => "Yes / No ");
        $lists["database_type"]= [$field->database_type => $lists["database_type"][$field->database_type]]+$lists["database_type"]; //TODO : gérer la conversion vers une valeur de tableau connue

        $lists["required"]=  array(0 => "Auto generated", 1 => "Required",2 => "Strongly advised",3 => "Advised",4 => "If possible",100 => "Undefined");
        $lists["required"]= [$field->getRequiredId() => $lists["required"][$field->getRequiredId()]]+$lists["required"]; //TODO : gérer la conversion vers une valeur de tableau connue

        $lists["status"]=   array(0 => "Disabled",1 => "Website",2 => "Website & App");
        $lists["status"]= [$field->getStatusId() => $lists["status"][$field->getStatusId()]]+$lists["status"]; //TODO : gérer la conversion vers une valeur de tableau connue

        if(empty($linked_list_id)){
            $lists["linked_list"] = [" " => "Choose an associate list"];
        }
        else{
            $lists["linked_list"] =  array_column(\App\Models\ListControl::where('id', $linked_list_id)->get()->toArray(), "title", "id");
        }
        $lists["linked_list"] +=  array_column(\App\Models\ListControl::where("deleted",0)->where("id","!=",$linked_list_id)->get()->toArray(), "title", "id");
        return view("fields.edit", compact('field', "lists"));
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
        $to_update = $request->validated();
        $to_update["database_type"] = $field->database_type; $to_update["validation_laravel"] = Field::getValidationLaravelFromForm($to_update);
        $field->update($to_update);

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

    /**
     * Returns a json which contains all the fields
     *
     * @param Request $request
     */
    public function handleApiRequest(Request $request)
    {
        if($request->user()->tokenCan("read")){
            $datas = array();
            $datas["fields"] = Field::getAPIContent();
            foreach (Field::getUsedLinkedList() as $list){
                $datas[$list] = $list::getAPIContent();
            }
            return response(json_encode($datas), 200)->header('Content-Type', 'application/json');
        }
        return response("Your token can't be use to read datas", 403);

    }
}
