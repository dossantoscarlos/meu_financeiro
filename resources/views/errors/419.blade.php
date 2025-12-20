@extends('errors::minimal')
@section('title', __('Error 419'))
@section('code', '419')
@section('message', __($exception->getMessage() ?: 'Error 419'))
