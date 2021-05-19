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
            ->where("links.deleted", 0)
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

            Column::name('from')
                ->hide(),
            Column::name('to')
                ->hide(),

            Column::name("from_refugee.full_name")
                ->searchable("from_refugee.full_name")
                ->view("datatable.linktoFrom")
                ->filterable()
                ->label('From name'),

            Column::name('relations.' . Relation::getDisplayedValue())
                ->label("Relation")
                ->alignCenter()
                ->filterable(Relation::pluck(Relation::getDisplayedValue())),

            Column::name("to_refugee.full_name")
                ->searchable("to_refugee.full_name")
                ->view("datatable.linktoTo")
                ->filterable()
                ->label('To name'),

            Column::callback(['id'], function ($id) {
                return "<a href='" . route('links.show', $id) . "'>Edit</a>";
            })
                ->label('Edit')
        ];
    }
}
