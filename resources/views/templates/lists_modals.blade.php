<div class="modal fade" id="edit-list-{{ $list->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Создание списка</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="editListForm">
                    <input type="hidden" name="list_id" value="{{ $list->id }}">
                    <div class="mb-3">
                        <label for="title" class="col-form-label">Название:</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $list->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="perms" class="col-form-label">Кому доступен:</label>
                        <select name="perms[]" class="form-control" id="perms" multiple>
                            @foreach($users as $user)
                                <option value="{{ $user['id'] }}" @if(!empty($user['list_ids']) && in_array($list->id, $user['list_ids'])) selected @endif >{{ $user['login'] }}</option>
                            @endforeach
                        </select>
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
