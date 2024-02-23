@extends('errors::minimal')

@section('title', __('errors/errors.server_error'))
@section('code', '500')
@section('message', __('errors/errors.server_error'))
