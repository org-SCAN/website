<?php

namespace App\Http\Livewire;

use App\Models\ListCountry;
use App\Models\ListGender;
use App\Models\ListRole;
use App\Models\Refugee;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class RefugeesDatatables extends LivewireDatatable
{
    public $model = Refugee::class;

    //public $complexQuery = true;


    public function builder()
    {
        return Refugee::query()
            ->leftJoin("api_logs", "api_logs.id", "refugees.api_log")
            ->leftJoin("crews", "crews.id", "api_logs.crew_id")
            ->where('crews.id', '=', Auth::user()->crew->id)
            ->orderByDesc("date");

    }

    /**
     * Write code on Method
     *
     * @return array
     */
    public function columns()
    {/*
        $arr = [];
        foreach(Field::where("descriptive_value", 1)->orderByDesc("best_descriptive_value")->get() as $field){
            array_push($arr,
                Column::name("field_refugee.value")
                    ->label($field->title)
            );
           // var_dump($field->id);
            break;
        }

        return $arr;*/
        /*
                $arr = [];
                $i = 1;
                foreach(Field::where("descriptive_value", 1)->orderBy("order")->get() as $field){
                    if(!empty($field->linked_list)){
                        $filter = ListControl::find($field->linked_list)->getListDisplayedValue();
                    }else{
                        $filter = "";
                    }
                    array_push($arr,
                        Column::callback(['id', "field_refugee.field_id"] , function ($id, $field) {
                            $field = Field::find($field);
                            $reference = Refugee::with(['crew' => function ($query) {
                                $query->where('crews.id', Auth::user()->crew->id);
                            }])
                                ->find($id)
                                ->fields->where("id", $field->id)->first();
                            $person_detail = $reference ? $reference->pivot->value : "";
                            if(!empty($field->linked_list)){//idrandom
                                $list = ListControl::find($field->linked_list); //role -> ListRole
                                $model = "App\Models\\".$list->name; // App\Models\ListRole
                                $person_detail = $model::find($person_detail)->{$list->displayed_value};
                            }
                            if($field->best_descriptive_value == 1){
                                $person_detail = "<a href='" . route('person.show', Refugee::find($id)) . "'>$reference</a>";
                            }
                            return $person_detail;
                        }, $i++)
                            ->label($field->title)
                            ->filterable($filter)
                    );
                }
                return $arr;*/
        // var_dump($arr);
        return [

            Column::callback('id', function ($id) {
                $reference = Refugee::with(['crew' => function ($query) {
                    $query->where('crews.id', Auth::user()->crew->id);
                }])
                    ->find($id)
                    ->fields->where("label", "unique_id")->first();
                return $reference ? $reference->pivot->value : "";
            }, "1")
                ->label('Reference')
                ->filterable(),

            Column::callback('id', function ($id) {
                $name = Refugee::with(['crew' => function ($query) {
                    $query->where('crews.id', Auth::user()->crew->id);
                }])
                    ->find($id)
                    ->fields
                    ->where("label", "full_name")
                    ->first();
                $name = $name ? $name->pivot->value : "";
                return "<a href='" . route('person.show', Refugee::find($id)) . "'>$name</a>";
            }, "2")
                ->filterable()
                ->label('Name'),

            Column::callback('id', function ($id) {
                $displayed = ListGender::getDisplayedValue();
                $gender = Refugee::with(['crew' => function ($query) {
                    $query->where('crews.id', Auth::user()->crew->id);
                }])
                    ->find($id)
                    ->fields
                    ->where("label", "gender")
                    ->first();

                return $gender ? ListGender::find($gender->pivot->value)->$displayed : "";
            }, "3")
                ->label("Sex")
                ->filterable(ListGender::pluck(ListGender::getDisplayedValue())),

            Column::callback('id', function ($id) {
                $displayed = ListCountry::getDisplayedValue();
                $country = Refugee::with(['crew' => function ($query) {
                    $query->where('crews.id', Auth::user()->crew->id);
                }])
                    ->find($id)
                    ->fields
                    ->where("label", "nationality")
                    ->first();
                return $country ? ListCountry::find($country->pivot->value)->$displayed : "";
            }, "4")
                ->filterable(ListCountry::pluck(ListCountry::getDisplayedValue()))
                ->label('nationality'),

            Column::callback('id', function ($id) {
                $displayed = ListRole::getDisplayedValue();
                $role = Refugee::with(['crew' => function ($query) {
                    $query->where('crews.id', Auth::user()->crew->id);
                }])
                    ->find($id)
                    ->fields
                    ->where("label", "role")
                    ->first();
                return $role ? ListRole::find($role->pivot->value)->$displayed : "";
            }, "5")
                ->filterable(ListRole::pluck(ListRole::getDisplayedValue()))
                ->label('role'),

            DateColumn::name('date')
                ->label('Date (from / to)')
                ->defaultSort('desc')
                ->filterable()

        ];
        /*
        return [
            // Column::checkbox(),

            Column::name('unique_id')
                ->label('Reference')
                ->filterable(),

            Column::callback(["full_name", 'id'], function ($name, $id) {
                return "<a href='" . route('person.show', $id) . "'>$name</a>";
            })
                ->filterable()
                ->label('Name'),

            Column::name('genders.' . ListGender::getDisplayedValue())
                ->label("Sex")
                ->filterable(ListGender::pluck(ListGender::getDisplayedValue())),

            Column::name('countries.' . ListCountry::getDisplayedValue())
                ->label("Nationality")
                ->filterable(),

            Column::name('roles.' . ListRole::getDisplayedValue())
                ->label("ListRole")
                ->filterable(ListRole::pluck(ListRole::getDisplayedValue())),

            DateColumn::name('date')
                ->label('Date (from / to)')
                ->defaultSort('desc')
                ->filterable()
        ];
        */
    }

}
