@extends('errors.layout')

@section('title', __('Forbidden'))
@section('code', '403')
@section('short_message', __('Access Denied'))
@section('message', __($exception->getMessage() ?: 'You don\'t have permission to access this resource. Please contact the administrator if you believe this is a mistake.'))
