<?php

namespace App\Http\Livewire;


use App\Models\Field;
use App\Models\Link;
use App\Models\Relation;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class LinksDatatables extends LivewireDatatable
{
    public $model = Link::class;


    public function builder()
    {
        /*return Link::query()
            ->whereNull("links.deleted_at")
            ->leftJoin('refugees as from_refugee', 'from_refugee.id', 'links.from')
            ->leftJoin('refugees as to_refugee', 'to_refugee.id', 'links.to')
            ->leftJoin('relations', 'relations.id', 'links.relation');

        */
        return Link::query();
    }

    /**
     * Write code on Method
     *
     * @return array
     */
    public function columns()
    {
        $arr = [];
        foreach (Field::all() as $field) {
            array_push($arr,
                Column::callback('id', function ($id, $field) {
                    return Link::find($id)
                        ->fields->where("label", $field->label)->first()->pivot->value;
                })->label($field->title), (string)$field->id);
        }

        return [
            Column::callback('id', function ($id) {
                $ref = Link::find($id)->refugeeFrom;
                $nameFrom = $ref->best_descriptive_value;
                return "<a href='" . route('person.show', $ref->id) . "'>$nameFrom</a>";
            }, ["1"])
                /*
                ->searchable("to_refugee.full_name")
                ->view("datatable.linktoTo")
                */
                ->filterable()
                ->label('From name'),

            Column::callback('id', function ($id) {
                return Link::find($id)->relation;
            }, ["2"])
                ->filterable(Relation::pluck(Relation::getDisplayedValue()))
                ->label('Relation')
                ->alignCenter(),

            Column::callback('id', function ($id) {
                $ref = Link::find($id)->refugeeTo;
                $nameTo = $ref->best_descriptive_value;
                return "<a href='" . route('person.show', $ref->id) . "'>$nameTo</a>";
            }, ["3"])
                /*
                ->searchable("to_refugee.full_name")
                ->view("datatable.linktoTo")
                */
                ->filterable()
                ->label('To name')
                ->alignRight(),

            Column::callback('id', function ($id) {
                if (Auth::user()->hasPermission("links.edit")) {
                    return "<a href='" . route('links.edit', $id) . "'>Edit</a>";
                }
                return "Ø";
            }, ["4"])
                ->label('Edit')
        ];
    }
}
