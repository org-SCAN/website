<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRefugeeRequest;
use App\Http\Requests\JsonFileRefugeeRequest;
use App\Http\Requests\StoreRefugeeApiRequest;
use App\Http\Requests\StoreRefugeeRequest;
use App\Http\Requests\UpdateRefugeeRequest;
use App\Imports\RefugeesImport;
use App\Models\Crew;
use App\Models\Duplicate;
use App\Models\Field;
use App\Models\Link;
use App\Models\Refugee;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;


class RefugeeController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct(
    )
    {
        $this->authorizeResource(Refugee::class,
            'person');
    }

    /**
     * This function is used to return the get person API request. It should be used only by the API.
     * It returns the list of all the person for a given crew. By default, the crew of the user, if a crew id is given, it will return the person of the given crew.
     * It never returns the person that are not in the given crew.
     * The person must have get permission to be returned.
     *
     * @param  Request  $request
     * @param  Crew|null  $crew
     * @return Response
     */

    public static function apiGetPerson(
        Request $request,
        Crew $crew = null
    ) {

        if ($request->user()->tokenCan("read")) {
            if ($crew == null) {
                $crew = $request->user()->crew;
            }
            $persons = $crew->persons;

            $responseArray = Refugee::formatRefugeesData($persons);


            return response(json_encode($responseArray),
                200,
                ['Content-type' => 'application/json']);
        }

        Log::info("Bad access token",
            ['user' => $request->user()]);

        return response("Your token can't be use to get data",
            403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRefugeeRequest  $request
     * @param  Refugee  $person
     * @return RedirectResponse
     */
    public function update(
        UpdateRefugeeRequest $request,
        Refugee $person
    ) {

        $ids = array_column($person->fields->toArray(),
            "id");
        $values = array_column(array_column($person->fields->toArray(),
            "pivot"),
            "value");
        $old = array_combine($ids,
            $values);

        $validated = array_map((function (
            $value
        ) {
            if (is_array($value)) {
                $value = json_encode(array_filter($value));
                if ($value == "[]") {
                    $value = null;
                }
            }
            return $value;
        }),
            $request->validated());

        foreach (array_diff($validated,
            $old) as $key => $value) {
            if (!empty($value)) {
                if ($person->fields()->wherePivot('field_id',
                    $key)->exists()) {
                    $person->fields()->updateExistingPivot($key,
                        ["value" => $value]);
                } else {
                    $person->fields()->attach([$key => ['value' => $value]]);
                }
                unset($old[$key]);
                unset($request->validated()[$key]);
            }
        }


        //detach fields that are not in the request
        foreach (array_diff($old,
            $validated) as $key => $value) {
            $person->fields()->detach($key);
        }
        //$refugee["date"] = ((isset($refugee["date"]) && !empty($refugee["date"])) ? $refugee["date"] : date('Y-m-d H:i', time()));

        $refugee = [];
        $person->update($refugee);
        return redirect()->route("person.index");
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {

        $fields = Field::where("crew_id",
            Auth::user()->crew->id)->where("descriptive_value",
            1)->orderBy("order")->get();

        $refugees = Auth::user()->crew->persons()->orderByDesc("date")->get();
        $refugees = Refugee::formatRefugeesData($refugees);

        return view("person.index",
            compact("refugees",
                "fields"));
    }

    /**
     * Display the specified resource.
     *
     * @param  Refugee  $person
     * @return View
     **/
    public function show(
        Refugee $person
    ) {
        $fields = Field::where("status",
            ">",
            0)->where("crew_id",
            Auth::user()->crew->id)->orderBy("required")->orderBy("order")->get();

        $links = Link::where("from",
            $person->id)->orWhere("to",
            $person->id)->get();

        $markers = $person->allCoordinates()->map(function (
            $item,
            $key
        ) {
            $value = json_decode($item->pivot->value,
                true);
            if (!empty($value)) {
                return [
                    "lat" => $value["lat"],
                    "lng" => $value["long"],
                    "title" => $item->title,
                    'popup' => '<em style="font-size : 1.3em">'.$item->title.'</em>',
                ];
            }
            return null;
        });

        $center = [
            "lat" => 0,
            "lng" => 0,
        ];
        if ($markers->count() > 0) {
            $center = $markers->reduce(function (
                $carry,
                $item
            ) {
                $carry["lat"] += $item["lat"];
                $carry["lng"] += $item["lng"];
                return $carry;
            }, [
                "lat" => 0,
                "lng" => 0,
            ]);
            $center["lat"] = $center["lat"] / $markers->count();
            $center["lng"] = $center["lng"] / $markers->count();
        }


        return view("person.show",
            compact("person",
                "fields",
                "links",
                "markers",
                "center"));
    }

    /**
     * Show the form for creating a new resource from a json file.
     *
     * @return View
     */
    public function createFromJson(
    )
    {
        return view("person.create_from_json");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRefugeeRequest  $request
     * @return RedirectResponse
     */
    public function store(
        StoreRefugeeRequest $request
    ) {
        $fields = $request->validated();
        foreach ($fields as $key => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    $value = json_encode(array_filter($value));
                    // if value == [] then it is empty, so we don't need to store it
                    if ($value == "[]") {
                        continue;
                    }

                }
                $ref[$key] = ["value" => $value];
            }
        }
        //
        $refugee["date"] = date('Y-m-d H:i',
            time());
        $refugee["crew_id"] = Auth::user()->crew->id;
        $new_ref = Refugee::create($refugee);
        $new_ref->fields()->attach($ref);

        return redirect()->route("person.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {

        $fields = Field::where("status",
            ">",
            0)->where("crew_id",
            Auth::user()->crew->id)->orderBy("required")->orderBy("order")->get();

        return view("person.create",
            compact("fields"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Refugee  $person
     * @return View
     */
    public function edit(
        Refugee $person
    ) {

        $fields = Field::where("status",
            ">",
            0)->where("crew_id",
            Auth::user()->crew->id)->orderBy("required")->orderBy("order")->get();

        $ids = array_column($person->fields->toArray(),
            "id");
        $values = array_column(array_column($person->fields->toArray(),
            "pivot"),
            "value");
        $refugee_detail = array_combine($ids,
            $values);

        return view("person.edit",
            compact("person",
                "fields",
                "refugee_detail"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  String  $refugee_id
     * @return RedirectResponse
     */
    public function fixDuplicatedReference(
        Request $request,
        $refugee_id
    ) {

        $refugee["unique_id"] = $request->input('unique_id');

        Refugee::find($refugee_id)->update($refugee);

        return redirect(URL::previous());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Refugee  $person
     * @return RedirectResponse
     */
    public function destroy(
        Refugee $person
    ) {
        $person->delete();
        // Delete the associated duplicate
        $duplicates = Duplicate::where("person1_id",
            $person->id)->orWhere("person2_id",
            $person->id)->get();
        foreach ($duplicates as $duplicate) {
            $duplicate->forceDelete();
        }
        return redirect()->route("person.index");
    }

    public function import(
        FileRefugeeRequest $request
    ) {

        // based on the file extension, we have to use a different reader
        $extension = $request->file('import_person_file')->getClientOriginalExtension();
        if ($extension == "json") { // if the file is a json file
            // call storeFromJson and cast the request as a JsonFileRefugeeRequest
            return $this->storeFromJson($request);
        } else { // else the file is a csv file
            try {
                Excel::import(new RefugeesImport,
                    $request->file('import_person_file'));
            } catch (Throwable $e) {
                return redirect()->back()->with('error',
                    'Something went wrong');
            }
        }
        return redirect()->route("person.index");
    }

    /**
     * Store a newly created resource from json file in storage.
     *
     * @param  JsonFileRefugeeRequest  $request
     * @return RedirectResponse
     */
    public function storeFromJson(
        Request $request
    ) {


        $validator = Validator::make(json_decode($request->import_person_file->getContent(),
            true),
            (new JsonFileRefugeeRequest())->rules());

        foreach ($validator->validated() as $refugee) {
            $refugee["date"] = ((!empty($refugee["date"])) ? $refugee["date"] : date('Y-m-d H:i',
                time()));
            $refugee["crew_id"] = Auth::user()->crew->id;
            $stored_ref = Refugee::handleApiRequest($refugee);
        }
        return redirect()->route("person.index");
    }

    /**
     * Handle the API request
     *
     * @param  StoreRefugeeApiRequest  $request
     * @return Response
     */
    public static function handleApiRequest(
        StoreRefugeeApiRequest $request
    ) {
        $responseArray = [];
        if ($request->user()->tokenCan("update")) {
            foreach ($request->validated() as $person) {
                $person["application_id"] = $request->header('Application-id') ?? $request->validated()[0]['application_id'] ?? 'website';
                $person["crew_id"] = $request->user()->crew->id;
                $stored_person = Refugee::handleApiRequest($person);

                if ($stored_person instanceof Response) {
                    return $stored_person;
                } elseif ($stored_person instanceof Refugee) {
                    $responseArray[] = $stored_person->id;
                }

            }
            return response(json_encode($responseArray),
                201,
                ['Content-type' => 'application/json']);
        }
        Log::info("Bad access token",
            ['user' => $request->user()]);
        return response("Your token can't be use to send data",
            403);
    }
}
