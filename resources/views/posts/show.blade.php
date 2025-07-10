@extends('layouts.app')

@section('title', __( 'posts/show.label_title'))

@section('content')
    <h1>{{ __('posts/show.label_title') }}</h1>
    <p><strong>{{ __('posts/show.label_title') }}:</strong> {{ $post->title }}</p>
    <p><strong>{{ __('posts/show.label_user_id') }}:</strong> {{ $post->user_id }}</p>
    <p><strong>{{ __('posts/show.label_content') }}:</strong> {{ $post->content }}</p>
@endsection
