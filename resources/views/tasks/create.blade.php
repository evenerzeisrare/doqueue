@extends('layouts.app')

@section('content')
<section class="page-heading"><div><p class="eyebrow">New task</p><h1>Add a task</h1><p class="muted">Capture the details now so your next step is clear.</p></div></section>
<section class="form-panel"><form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data">@include('tasks.form', ['task' => null, 'submitLabel' => 'Save task'])</form></section>
<section class="panel subject-panel"><div><p class="eyebrow">Organize your work</p><h2>Add a subject</h2><p class="muted">Create a subject here, then select it above.</p></div><form class="subject-form" method="POST" action="{{ route('subjects.store') }}">@csrf<input type="text" name="name" placeholder="e.g. Mathematics" required><button class="button button-muted" type="submit">Add subject</button></form></section>
@endsection
