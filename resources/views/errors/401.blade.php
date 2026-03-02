@extends('errors.layout')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('short_message', __('Unauthorized'))
@section('message', __('You must be authenticated to access this page. Please log in to continue.'))
