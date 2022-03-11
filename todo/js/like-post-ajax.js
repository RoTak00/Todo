/*$('.likebtn').click(function()
    {
        var btn_id = this.id;
        var split_id = btn_id.split("-");
        alert("click");
        var task_id = split_id[1];

        $.ajax(
            {
                url: '/php/like-task-ajax.php',
                type: 'post',
                async: "true",
                data:
                {
                    php_taskid:task_id
                },
                dataType: 'json',
                success: function(data)
                {
                    let error = data['error'];
                    if(error != "")
                    {
                        $('#error-div').html("error");
                    }
                    else
                    {
                        let status = data['status'];

                        if(status == "not_liked")
                        {
                            $('#liketext1-'+task_id).html("Apreciază");
                            $('#liketext2-'+task_id).html("Apreciază");

                            $('#likeicon1-'+task_id).css("color", "black");
                            $('#likeicon1-'+task_id).removeClass("fas");
                            $('#likeicon1-'+task_id).addClass("far");
                            $('#likeicon2-'+task_id).css("color", "black");
                            $('#likeicon2-'+task_id).removeClass("fas");
                            $('#likeicon2-'+task_id).addClass("far");
                        }
                        else if(status == "liked")
                        {
                            $('#liketext1-'+task_id).html("Apreciezi");
                            $('#liketext2-'+task_id).html("Apreciezi");

                            $('#likeicon1-'+task_id).css("color", "red");
                            $('#likeicon1-'+task_id).removeClass("far");
                            $('#likeicon1-'+task_id).addClass("fas");
                            $('#likeicon2-'+task_id).css("color", "red");
                            $('#likeicon2-'+task_id).removeClass("far");
                            $('#likeicon2-'+task_id).addClass("fas");
                        }

                        let likecount = data['likes'];
                        let liketext = (likecount == 1 ? " punct": " puncte");
                        $('#likecount1-'+task_id).html("<strong>"+likecount+"</strong>" + liketext);
                        $('#likecount2-'+task_id).html("<strong>"+likecount+"</strong>" + liketext);
                    }
                }
            }
        );
    });
*/

function liketask(task_id)
{

        $.ajax(
            {
                url: '/php/like-task-ajax.php',
                type: 'post',
                async: "true",
                data:
                {
                    php_taskid:task_id
                },
                dataType: 'json',
                success: function(data)
                {
                    let error = data['error'];
                    if(error != "")
                    {
                        $('#error-div').html("error");
                    }
                    else
                    {
                        let status = data['status'];

                        if(status == "not_liked")
                        {
                            $('#liketext1-'+task_id).html("Apreciază");
                            $('#liketext2-'+task_id).html("Apreciază");

                            $('#likeicon1-'+task_id).css("color", "black");
                            $('#likeicon1-'+task_id).removeClass("fas");
                            $('#likeicon1-'+task_id).addClass("far");
                            $('#likeicon2-'+task_id).css("color", "black");
                            $('#likeicon2-'+task_id).removeClass("fas");
                            $('#likeicon2-'+task_id).addClass("far");
                        }
                        else if(status == "liked")
                        {
                            $('#liketext1-'+task_id).html("Apreciezi");
                            $('#liketext2-'+task_id).html("Apreciezi");

                            $('#likeicon1-'+task_id).css("color", "red");
                            $('#likeicon1-'+task_id).removeClass("far");
                            $('#likeicon1-'+task_id).addClass("fas");
                            $('#likeicon2-'+task_id).css("color", "red");
                            $('#likeicon2-'+task_id).removeClass("far");
                            $('#likeicon2-'+task_id).addClass("fas");
                        }

                        let likecount = data['likes'];
                        let liketext = (likecount == 1 ? " punct": " puncte");
                        $('#likecount1-'+task_id).html("<strong>"+likecount+"</strong>" + liketext);
                        $('#likecount2-'+task_id).html("<strong>"+likecount+"</strong>" + liketext);
                    }
                }
            }
        );
}