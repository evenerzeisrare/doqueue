@csrf
<div class="form-grid">
    <label class="field field-wide">Task title<input type="text" name="title" value="{{ old('title', $task?->title) }}" placeholder="e.g. Mathematics assignment" required></label>
    <label class="field">Subject<select name="subject" required><option value="">Select a subject</option>@foreach($subjects as $subject)<option value="{{ $subject->name }}" @selected(old('subject', $task?->subject) === $subject->name)>{{ $subject->name }}</option>@endforeach</select></label>
    <label class="field">Due date<input type="date" name="due_date" value="{{ old('due_date', $task?->due_date?->format('Y-m-d')) }}" required></label>
    <label class="field">Due time<input type="time" name="due_time" value="{{ old('due_time', $task?->due_time) }}"></label>
    <label class="field">Priority<select name="priority" required>@foreach(['Low', 'Medium', 'High'] as $priority)<option value="{{ $priority }}" @selected(old('priority', $task?->priority ?? 'Medium') === $priority)>{{ $priority }}</option>@endforeach</select></label>
    <label class="field">Status<select name="status" required>@foreach(['To Do', 'In Progress', 'Done'] as $status)<option value="{{ $status }}" @selected(old('status', $task?->status ?? 'To Do') === $status)>{{ $status }}</option>@endforeach</select></label>
    <label class="field field-wide">Description<textarea name="description" rows="5" placeholder="Add a few helpful details">{{ old('description', $task?->description) }}</textarea></label>
    <label class="field field-wide">Attachment <span class="field-help">PDF, document, image, or school file up to 10 MB</span><input type="file" name="attachment" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.gif,.txt,.ppt,.pptx,.xls,.xlsx">@if($task?->attachment_path)<span class="attachment-note">Current file: {{ basename($task->attachment_path) }}. Upload a new file to replace it.</span>@endif</label>
</div>
<div class="form-actions"><a class="button button-muted" href="{{ route('tasks.index') }}">Cancel</a><button class="button button-primary" type="submit">{{ $submitLabel }}</button></div>
