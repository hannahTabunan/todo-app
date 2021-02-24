$(document).ready(function() {
    fetchList();
});

// fetch list from API
function fetchList(param = '') {
    param = param ? `?${param}` : '';

    $.ajax({
        type: "GET",
        url: `../back/api/list.php${param}`,
        success: function (resp) {
            if (resp.code === 200) {
                $('#pending').find('*').not('h3').remove();
                $('#in_progress').find('*').not('h3').remove();
                $('#completed').find('*').not('h3').remove();

                resp.data.forEach(prepareCardDragDrop);
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

// prepare card
function prepareCardDragDrop(item, index) {
    let category = '#pending';

    if (item.status === 'P') {
        category = '#pending';
    } else if (item.status === 'IP') {
        category = '#in_progress';
    } else if (item.status === 'C') {
        category = '#completed';
    }

    // append the card element to its respective category
    $(category).append(`
        <div class="card" draggable="true" id="card_${item.id}">
            <div class="card-body">
                <div class="form-check form-check-inline">
                  <label class="form-check-label task-title ${item.status === 'C' ? 'completed' : ''}">${item.task}</label>
                </div>
                <button type="button" class="btn btn-danger btn-sm float-right delete-task" onclick="onDelete(${item.id}, this)">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    `);

    // load the drag and drop events
    prepareDragDropEvents();
}

// create
function onCreate(e, elem) {
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: '../back/api/create.php',
        data: $(elem).serialize(),
        success: function (resp) {
            if (resp.code === 200) {
                prepareCardDragDrop(resp.data);
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

// update status
function onUpdateStatus(id, status, elem, cb) {
    $.ajax({
        type: "POST",
        url: `../back/api/update.php`,
        data: `id=${id}&status=${status}`,
        success: function (resp) {
            if (resp.code === 200) {
                if (cb) {
                    cb(elem);
                }
            } else {
                alert(resp);
            }
        }
    })
}

function onSort(elem) {
    let direction = $(elem).hasClass('bi-sort-alpha-down') ? 'asc' : 'desc';
    let params = `sort=${direction}`;

    if (direction === 'asc') {
        $(elem).removeClass('bi-sort-alpha-down');
        $(elem).addClass('bi-sort-alpha-up');
    } else {
        $(elem).addClass('bi-sort-alpha-down');
        $(elem).removeClass('bi-sort-alpha-up');
    }

    fetchList(params);
}

// drag and drop
let cards = [];
const categories = document.querySelectorAll('.category');

function prepareDragDropEvents() {
    cards = document.querySelectorAll('.card');

    // card listeners
    for (const card of cards) {
        card.addEventListener('dragstart', dragStart);
        card.addEventListener('dragend', dragEnd);
    }

    // Loop through empty boxes and add listeners
    for (const category of categories) {
        category.addEventListener('dragover', dragOver);
        category.addEventListener('dragenter', dragEnter);
        category.addEventListener('dragleave', dragLeave);
        category.addEventListener('drop', dragDrop);
    }
}

// Drag Functions
function dragStart(e) {
    this.className += ' hold';
    // pass the card ID
    e.dataTransfer.setData('Text/html', e.target.id);
    setTimeout(() => (this.className = 'invisible'), 0);
}

function dragEnd() {
    this.className = 'card';
}

function dragOver(e) {
    e.preventDefault();
}

function dragEnter(e) {
    e.preventDefault();
    this.className += ' hovered';
}

function dragLeave() {
    this.className = 'category';
}

function dragDrop(e, target) {
    let card_id = e.dataTransfer.getData("text/html");
    let id = card_id.split('_')[1];
    let cardElem = document.querySelector(`#${card_id}`);
    let status = 'P';

    if (e.target.id === 'pending') {
        status = 'P';
    } else if (e.target.id === 'in_progress') {
        status = 'IP';
    } else if (e.target.id === 'completed') {
        status = 'C';
    }

    onUpdateStatus(id, status, this, function (elem) {
        elem.className = 'category';
        elem.append(cardElem);
    });
}