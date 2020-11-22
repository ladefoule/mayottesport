@extends('errors::illustrated-layout')

@section('title', __('Service non disponible'))
@section('code', '503')
@section('message', 'Service non disponible'))
{{-- @section('message', __($exception->getMessage() ?: 'Service non disponible')) --}}
