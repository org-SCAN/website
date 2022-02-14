<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\Gender;
use App\Models\Refugee;
use App\Models\Role;
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
        /*return Refugee::query()
            ->whereNull("refugees.deleted_at")
            ->leftJoin('countries', 'countries.id', 'refugees.nationality')
            ->leftJoin('roles', 'roles.id', 'refugees.role')
            ->leftJoin('genders', 'genders.id', 'refugees.gender');
        */
        /*
                $fields = Refugee::first()->fields;
                foreach($fields as $field){
                    var_dump($field->label." : ".$field->pivot->value);
                }
                die();*/
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
    {
        /*$arr = [];
        foreach(Field::where("descriptive_value", 1)->get() as $field){
            array_push($arr,
                Column::callback('id', function ($id, $field) {
                    return Refugee::find($id)
                        ->fields->where("label", $field->label)->first()->pivot->value;
                })->label($field->title), (string) $field->id);
        }*/
        //return $arr;
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
                return "<a href='" . route('manage_refugees.show', $id) . "'>$name</a>";
            }, "2")
                ->filterable()
                ->label('Name'),

            Column::callback('id', function ($id) {
                $displayed = Gender::getDisplayedValue();
                $gender = Refugee::with(['crew' => function ($query) {
                    $query->where('crews.id', Auth::user()->crew->id);
                }])
                    ->find($id)
                    ->fields
                    ->where("label", "gender")
                    ->first();

                return $gender ? Gender::find($gender->pivot->value)->$displayed : "";
            }, "3")
                ->label("Sex")
                ->filterable(Gender::pluck(Gender::getDisplayedValue())),

            Column::callback('id', function ($id) {
                $displayed = Country::getDisplayedValue();
                $country = Refugee::with(['crew' => function ($query) {
                    $query->where('crews.id', Auth::user()->crew->id);
                }])
                    ->find($id)
                    ->fields
                    ->where("label", "nationality")
                    ->first();
                return $country ? Country::find($country->pivot->value)->$displayed : "";
            }, "4")
                ->filterable(Country::pluck(Country::getDisplayedValue()))
                ->label('nationality'),

            Column::callback('id', function ($id) {
                $displayed = Role::getDisplayedValue();
                $role = Refugee::with(['crew' => function ($query) {
                    $query->where('crews.id', Auth::user()->crew->id);
                }])
                    ->find($id)
                    ->fields
                    ->where("label", "role")
                    ->first();
                return $role ? Role::find($role->pivot->value)->$displayed : "";
            }, "5")
                ->filterable(Role::pluck(Role::getDisplayedValue()))
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
                return "<a href='" . route('manage_refugees.show', $id) . "'>$name</a>";
            })
                ->filterable()
                ->label('Name'),

            Column::name('genders.' . Gender::getDisplayedValue())
                ->label("Sex")
                ->filterable(Gender::pluck(Gender::getDisplayedValue())),

            Column::name('countries.' . Country::getDisplayedValue())
                ->label("Nationality")
                ->filterable(),

            Column::name('roles.' . Role::getDisplayedValue())
                ->label("Role")
                ->filterable(Role::pluck(Role::getDisplayedValue())),

            DateColumn::name('date')
                ->label('Date (from / to)')
                ->defaultSort('desc')
                ->filterable()
        ];
        */
    }

}
