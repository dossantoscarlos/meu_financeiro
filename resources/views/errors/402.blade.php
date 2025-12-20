@extends('errors::minimal')
@section('title', __('Error 402'))
@section('code', '402')
@section('message', __($exception->getMessage() ?: 'Error 402'))
