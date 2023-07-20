<div class="col-3 pb-lg-3 task-card" data-task-id="{{ $task->id }}">
    <div class="card shadow-sm text-center">
        <a href="/{{ $task->img }}" target="_blank"><img width="150" src="/{{ $task->img_min }}" alt=""></a>

        <div class="card-body">
            <p class="card-text">
                {{ $task->title }}
                <input type="checkbox" name="is_done" @if($task->status) checked @endif>
            </p>

            @foreach ($tags as $tag)
                <span class="border p-1 text-light bg-dark">{{ $tag->title }}</span>
            @endforeach

            <div class="card-body">
                <button type="button" class="btn btn-primary edit-task-modal" data-bs-toggle="modal" data-bs-target="#edit-task-{{ $task->id }}">Редактировать</button>
                <button type="button" class="btn btn-danger delete-task">Удалить</button>
            </div>
        </div>
    </div>

</div>
