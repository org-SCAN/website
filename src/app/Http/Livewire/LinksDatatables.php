<?php

namespace App\Http\Livewire;


use App\Models\Link;
use App\Models\Relation;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class LinksDatatables extends LivewireDatatable
{
    public $model = Link::class;


    public function builder()
    {
        return Link::query()
            ->leftJoin('refugees as from_refugee', 'from_refugee.id', 'links.from')
            ->leftJoin('refugees as to_refugee', 'to_refugee.id', 'links.to')
            ->leftJoin('relations', 'relations.id', 'links.relation');
    }

    /**
     * Write code on Method
     *
     * @return array
     */
    public function columns()
    {
        return [
            // Column::checkbox(),

            Column::callback(["from_refugee.full_name", 'from'], function ($nameFrom, $idFrom) {
                return "<a href='" . route('manage_refugees.show', $idFrom) . "'>$nameFrom</a>";
            })
                ->searchable()
                ->filterable()
                ->label('From name'),

            Column::name('relations.' . Relation::getDisplayedValue())
                ->label("Relation")
                ->alignCenter()
                ->filterable(Relation::pluck(Relation::getDisplayedValue())),

            Column::callback(["to_refugee.full_name", 'to'], function ($nameTo, $idTo) {
                return "<a href='" . route('manage_refugees.show', $idTo) . "'>$nameTo</a>";
            })
                ->searchable()
                ->filterable()
                ->label('To name'),

            Column::callback(['id'], function ($id) {
                return "<a href='" . route('links.show', $id) . "'>Edit</a>";
            })
                ->label('Edit')
        ];
    }
}
