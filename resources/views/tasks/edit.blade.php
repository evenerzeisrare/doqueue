@extends('layouts.app')

@section('content')
<section class="page-heading"><div><p class="eyebrow">Update task</p><h1>Edit task</h1><p class="muted">Keep the details current as your assignment changes.</p></div></section>
<section class="form-panel"><form method="POST" action="{{ route('tasks.update', $task) }}" enctype="multipart/form-data">@method('PUT') @include('tasks.form', ['submitLabel' => 'Save changes'])</form></section>
@endsection
