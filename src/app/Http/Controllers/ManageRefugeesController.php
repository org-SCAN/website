<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Http\Requests\StoreRefugeeRequest;
use App\Http\Requests\UpdateRefugeeRequest;
use App\Http\Requests\StoreRefugeeApiRequest;
use App\Models\ListControl;
use App\Traits\Uuids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PhpParser\Node\Expr\Array_;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Refugee;
use App\Models\Link;
use Illuminate\Support\Facades\View;


class ManageRefugeesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refugees = Refugee::where("deleted", 0)
            ->orderBy("date", "desc")
            ->get();

        return view("manage_refugees.index", compact("refugees"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $fields = Field::where("status", ">", 0)
            ->orderBy("required")
            ->orderBy("order")
            ->get();
        return view("manage_refugees.create", compact("fields"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRefugeeRequest $request)
    {
       var_dump($request->validated());
        $new_ref = Refugee::create($request->validated());

        return redirect()->route("manage_refugees.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $fields = Field::where("status", ">", 0)
            ->orderBy("required")
            ->orderBy("order")
            ->get();
        $refugee = Refugee::find($id);
        $links = Link::where("deleted",0)->where("refugee1", $id)->orWhere("refugee2", $id)->get();

        return view("manage_refugees.show", compact("refugee", "fields", "links"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(String  $id)
    {
        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $refugee = Refugee::find($id);
        $fields = Field::where("status", ">", 0)
            ->orderBy("required")
            ->orderBy("order")
            ->get();
        return view("manage_refugees.edit", compact("refugee", "fields"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\RequestRefugeeRequest $request
     * @param  String $refugee_id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRefugeeRequest $request, $refugee_id)
    {
        Refugee::where("id",$refugee_id)
            ->update($request->validated());
        return redirect()->route("manage_refugees.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $refugee_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($refugee_id)
    {

        //abort_if(Gate::denies('manage_refugees_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Refugee::where("id",$refugee_id)
            ->update(["deleted"=>1]);
        return redirect()->route("manage_refugees.index");
    }

    /**
     * Handle the API request
     *
     * @param  StoreRefugeeApiRequest  $request
     * @return array
     */
    public static function handleApiRequest(StoreRefugeeApiRequest $request)
    {
        if($request->user()->tokenCan("update")){
            foreach ($request->validated() as $refugee){
                $stored_ref = Refugee::create($refugee);
                if(!$stored_ref->exists){
                    return response("Error while creating this refugee :".json_encode($refugee), 500);
                }
            }
           return response("Success !", 201);
        }

        return response("Your token can't be use to send datas", 403);
    }
}
