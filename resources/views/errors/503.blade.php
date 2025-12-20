@extends('errors::minimal')
@section('title', __('Error 503'))
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'Error 503'))
