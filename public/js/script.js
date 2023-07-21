$(document).ready(function () {

    $('.tasks-search').on('click', function (e) {
        e.preventDefault();

        let data = {};
        data.search_string = $('#tasks-search').val();
        data.tags = $('select[name=tags]').val();
        data.list_id = $('#tasks-search').attr('data-list_id');

        $.ajax({
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/tasksSearch",
            data: data
        })
            .done(function (msg) {
                if (msg.status == 1) {
                    $('#tasks-container').html(msg.tasks);
                    $('#task_modals').html(msg.tasks_modals);
                } else {
                    alert('error');
                }
            });
    });

    $(document).on('click', '.list-card a', function (e) {
        e.preventDefault();
        let target = $( e.target );
        if ( target.is( "button" ) ) {
            return;
        }
        window.location.href = $(this).attr('href');
    });

    $(document).on('click', '.delete-list', function (e) {
        e.preventDefault();
        if (confirm('Вы уверены что хотите удалить список?')) {
            let data = {};
            data.list_id = $(this).closest('div.list-card').attr('data-list-id');

            let _this = $(this);

            $.ajax({
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/deleteList",
                data: data
            })
                .done(function (msg) {
                    if (msg.status == 1) {
                        _this.closest('.list-card').remove();
                    } else {
                        alert('error');
                    }
                });
        }
    });

    $(document).on('click', '.delete-task', function (e) {
        e.preventDefault();
        if (confirm('Вы уверены что хотите удалить задачу?')) {
            let data = {};
            data.task_id = $(this).closest('div.task-card').attr('data-task-id');

            let _this = $(this);

            $.ajax({
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/deleteTask",
                data: data
            })
                .done(function (msg) {
                    if (msg.status == 1) {
                        _this.closest('.task-card').remove();
                    } else {
                        alert('error');
                    }
                });
        }
    });

    $(document).on('change', 'input[name=img_delete]', function () {
        $(this).closest('.editTaskForm').find('.img_input').toggle();
    });

    $(document).on('submit', '.editTaskForm', function (e) {
        e.preventDefault();
        let formData = new FormData();
        let _this = $(this);
        formData.append('task_image', $(this).find("#task_image")[0].files[0]);
        formData.append('title', $(this).find("input[name=title]").val());
        formData.append('tags', $(this).find("input[name=tags]").val());
        formData.append('img_delete', $(this).find("input[name=img_delete]").prop('checked'));
        formData.append('task_id', $(this).find("input[name=task_id]").val());

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/editTask",
            contentType: false,
            processData: false,
            data: formData
        })
            .done(function( msg ) {
                if (msg.status == 1){
                    $('#tasks-container').html(msg.tasks);
                    _this.closest('.editTaskForm').find('button[data-bs-dismiss=modal]').click();
                    $('#task_modals').html(msg.tasks_modals);
                }else{
                    alert('error');
                }
            });


    });

    $(document).on('change', 'input[name=is_done]', function () {
        let data = {};
        data.status = $(this).prop('checked');
        data.task_id = $(this).closest('div.task-card').attr('data-task-id');

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/changeTaskStatus",
            data: data
        })
    });


    $(document).on('submit', '.editListForm', function (e) {
        e.preventDefault();
        let data = $(this).serializeArray();
        let _this = $(this);

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/editList",
            data: data
        })
            .done(function( msg ) {
                if (msg.status == 1){
                    $('#lists-container').html(msg.lists);
                    _this.closest('.editListForm').find('button[data-bs-dismiss=modal]').click();
                    $('#list_modals').html(msg.lists_modals);
                }else{
                    alert('error');
                }
            });
    });

    $(document).on('submit', '#addTaskForm', function (e) {
        e.preventDefault();
        let formData = new FormData();
        formData.append('task_image', $(this).find("#task_image")[0].files[0]);
        formData.append('title', $(this).find("input[name=title]").val());
        formData.append('tags', $(this).find("input[name=tags]").val());
        formData.append('list_id', $(this).find("input[name=list_id]").val());

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/addTask",
            contentType: false,
            processData: false,
            data: formData
        })
            .done(function( msg ) {
                if (msg.status == 1){
                    $('#tasks-container').html(msg.tasks);
                    $('#addTaskForm button[data-bs-dismiss=modal]').click();
                    $('#task_modals').html(msg.tasks_modals);
                }else{
                    alert('error');
                }
            });
    });

    $(document).on('submit', '#addListForm', function (e) {
        e.preventDefault();
        let data = $(this).serializeArray();

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/addList",
            data: data
        })
            .done(function( msg ) {
                if (msg.status == 1){
                    $('#lists-container').html(msg.lists);
                    $('#addListForm button[data-bs-dismiss=modal]').click();
                    $('#list_modals').html(msg.lists_modals);
                }else{
                    alert('error');
                }
            });
    });

    $(document).on('submit', '#loginForm', function (e) {
        e.preventDefault();
        let data = $(this).serializeArray();

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/login",
            data: data
        })
            .done(function( msg ) {
                if (msg == 1){
                    alert('success');
                    window.location.reload();
                }else{
                    alert('error');
                }
            });


    });

    $(document).on('submit', '#signUpForm', function (e) {
        e.preventDefault();
        let data = $(this).serializeArray();
        if ($(this).find('input#pass').val() === $(this).find('input#pass1').val()){
            $.ajax({
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/sign-up",
                data: data
            })
                .done(function( msg ) {
                    if (msg == 1){
                        alert('success');
                        window.location.reload();
                    }else{
                        alert('error');
                    }
                });
        }else{
            alert('Пароли должны совпадать');
            return;
        }

    });
});
