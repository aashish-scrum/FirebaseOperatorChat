@extends('layouts.app')

@section('content')
@php
    $authData = Auth::user();
    $authData->account = $companyUuid;
    $authData->widget = 'WDuQoN8';
@endphp
    <chat :user="{{ Auth::user() }}"/>
@endsection
