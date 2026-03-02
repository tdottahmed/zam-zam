@extends('errors.layout')

@section('title', __('Server Error'))
@section('code', '500')
@section('short_message', __('Server Error'))
@section('message', __('Whoops! Something went wrong on our end. We are looking into it and will have it fixed shortly.'))
