@extends('errors::minimal')

@section('title', __('errors/errors.too_many_requests'))
@section('code', '429')
@section('message', __('errors/errors.too_many_requests'))
