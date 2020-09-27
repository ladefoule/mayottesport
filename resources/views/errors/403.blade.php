{{-- @extends('errors::minimal') --}}
@extends('errors::illustrated-layout')

@section('title', 'Interdit')
@section('code', '403')
@section('message', $exception->getMessage() ?: 'Interdit')
