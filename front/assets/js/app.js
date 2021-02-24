$(document).ready(function() {
    fetchList();
});

// fetch list from API
function fetchList() {
    $.ajax({
        type: "GET",
        url: "../back/api/list.php",
        success: function (resp) {
            if (resp.code === 200) {
                $('.list').empty();
                resp.data.forEach(prepareCard);
            } else {
                alert(resp);
            }
        }
    })
}

function prepareCard(item, index) {
    $('.list').append(`
                    <div class="card">
                        <div class="card-body">
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" value="P"
                              ${item.status === 'C' ? 'checked' : ''} onchange="onCheck(${item.id}, this)"/>
                              <label class="form-check-label task-title ${item.status === 'C' ? 'completed' : ''}">${item.task}</label>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm float-right delete-task" onclick="onDelete(${item.id}, this)">
                                Delete
                            </button>
                        </div>
                    </div>
                `);
}

// create
function onCreate(e, elem) {
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: '../back/api/create.php',
        data: $(elem).serialize(),
        success: function (resp) {
            console.log(resp);

            if (resp.code === 200) {
                prepareCard(resp.data);
                $(elem).trigger("reset");
            } else {
                alert(resp);
            }
        }
    })
}

// delete
function onDelete(id, elem) {
    $.ajax({
        type: "GET",
        url: `../back/api/delete.php?id=${id}`,
        success: function (resp) {
            if (resp.code === 200) {
                let cardElem = $(elem).closest('.card');
                $(cardElem).hide('slow', function(){ $(cardElem).remove(); });
            } else {
                alert(resp);
            }
        }
    })
}

// update status
function onCheck(id, elem) {
    let status = $(elem).prop('checked') === true ? 'C' : 'P';

    $.ajax({
        type: "POST",
        url: `../back/api/update.php`,
        data: `id=${id}&status=${status}`,
        success: function (resp) {
            if (resp.code === 200) {
                let cardTitleElem = $(elem).closest('.card').find('.task-title')[0];

                if (status === 'C') {
                    $(cardTitleElem).addClass('completed');
                } else {
                    $(cardTitleElem).removeClass('completed');
                }
            } else {
                alert(resp);
            }
        }
    })
}