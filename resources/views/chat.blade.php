@extends('layouts.app')

@section('content')
@php
    $authData = Auth::user();
    $authData->account = $companyUuid;
    $authData->widget = 'WDuQoN8';
    $authData->depart = $departments;
@endphp
    <chat :user="{{ Auth::user() }}"/>
@endsection
