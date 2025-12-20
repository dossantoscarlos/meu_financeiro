@extends('errors::minimal')
@section('title', __('Error 500'))
@section('code', '500')
@section('message', __($exception->getMessage() ?: 'Error 500'))
