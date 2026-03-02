@extends('errors.layout')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('short_message', __('Under Maintenance'))
@section('message', __($exception->getMessage() ?: 'We are currently undergoing scheduled maintenance. Please check back later.'))
