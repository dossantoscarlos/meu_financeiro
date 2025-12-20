@extends('errors::minimal')
@section('title', __('Error 429'))
@section('code', '429')
@section('message', __($exception->getMessage() ?: 'Error 429'))
