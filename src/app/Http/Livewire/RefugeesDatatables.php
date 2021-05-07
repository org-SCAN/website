<?php

namespace App\Http\Livewire;

use App\Models\Refugee;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class RefugeesDatatables extends LivewireDatatable
{
    public $model = Refugee::class;


    public function builder()
    {
        return Refugee::query()
            ->leftJoin('countries', 'countries.id', 'refugees.nationality')
            ->leftJoin('roles', 'roles.id', 'refugees.role')
            ->leftJoin('genders', 'genders.id', 'refugees.gender');
    }

    /**
     * Write code on Method
     *
     * @return array
     */
    public function columns()
    {
        return [
            Column::name('unique_id')
                ->label('Reference')
                ->filterable()
                ->searchable(),

            Column::callback(["full_name", 'id'], function ($name, $id) {
                return "<a href='" . route('manage_refugees.show', $id) . "'>$name</a>";
            })
                ->defaultSort('asc')
                ->searchable()
                ->label('Name'),

            Column::name('genders.full')
                ->label("Sex")
                ->filterable([
                    'Male',
                    'Female',
                    'Other']),

            Column::name('countries.short')
                ->label("Nationality")
                ->filterable(),

            Column::name('roles.short')
                ->label("Role")
                ->filterable(),

            DateColumn::name('date')
                ->label('Date')
                ->filterable()
        ];
    }

}
