
<div class="col-3 pb-lg-3 list-card" data-list-id="{{ $id }}">
    <a href="{{ route('lists', $id) }}" class="text-decoration-none" style="color: black">
        <div class="card shadow-sm align-items-center ">
            <p class="card-text">{{ $title }}</p>

            <div class="card-body">
                <button type="button" class="btn btn-primary edit-task-modal" data-bs-toggle="modal" data-bs-target="#edit-list-{{ $id }}">Редактировать</button>
                <button type="button" class="btn btn-danger delete-list">Удалить</button>
            </div>

        </div>
    </a>
</div>

