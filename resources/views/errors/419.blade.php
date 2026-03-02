@extends('errors.layout')

@section('title', __('Page Expired'))
@section('code', '419')
@section('short_message', __('Session Expired'))
@section('message', __('Your session has expired due to inactivity. Please refresh the page or try logging in again.'))
