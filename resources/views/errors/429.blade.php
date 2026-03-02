@extends('errors.layout')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('short_message', __('Too Many Requests'))
@section('message', __('You are making too many requests to our servers. Please slow down and try again later.'))
