@extends('errors::minimal')
@section('title', __('Error 403'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Error 403'))
