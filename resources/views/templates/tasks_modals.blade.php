<div class="modal fade" id="edit-task-{{ $task->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Редактирование задачи</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="editTaskForm">
                    <input type="hidden" name="task_id" value="{{ $task->id }}" />
                    <div class="mb-3">
                        <label for="title" class="col-form-label">Название:</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $task->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tags" class="col-form-label">Теги (введите через запятую):</label>
                        <input type="text" class="form-control" id="tags" name="tags" value="{{ $tags }}">
                    </div>
                    @if(!empty($task->img))
                    <div class="mb-3">
                        <label for="img_delete" class="col-form-label">Удалить картинку:</label>
                        <input type="checkbox" class="form-control-file" id="img_delete" name="img_delete">
                    </div>
                    @endif
                    <div class="mb-3 img_input">
                        <label for="task_image" class="col-form-label">Картинка:</label>
                        <input type="file" class="form-control-file" id="task_image" name="task_image">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
