$(window).scroll(function()
{
    if($(document).height() <= $(window).scrollTop() + $(window).height())
    {    
        loadMoreTasks();
        
    }

    
});


$('#btn-loadmore').click(function()
    {
        loadMoreTasks();
    }
);

function loadMoreTasks()
{
    let row_number = $('#data-row-number').val();
    let tasksperload = $('#data-tasksperload').val();
    let pagetype = $('#data-pagetype').val();
    let userid = $('#data-userid').val();
    let f_state = $('#data-f_state').val();
    let visibility = $('#data-visibility').val();

    $.ajax(
        {
            type: 'post',
            url: '/php/get-tasks.php',
            async: "true",
            data:
            {
                php_row_number:row_number,
                php_tasksperload:tasksperload,
                php_pagetype:pagetype,
                php_requesteduserid:userid,
                php_f_state:f_state,
                php_visibility:visibility
            },
            success: function(response)
            {
                if(response != "ARR_EMPTY")
                {
                $('#data-row-number').val(Number(row_number) + Number(tasksperload));
                var content = $('#task-output-content').html();
                $('#task-output-content').html(content+response);
                }
                else
                {
                    $('#container-btn-loadmore').html("<p class = \"text-center\"> Nu mai există postări</p>");
                }
            }
        });
}

