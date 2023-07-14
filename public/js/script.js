$("#student_id").change(function (event) {
    /* Act on the event */
    var student_id = $(this).val();
    $("#subject_id").children().not(":first-child").remove();
    if (student_id == "") {
        return;
    }
    $("#load").html("Loading...");
    var url = $(this).attr("url");
    url = url.replace("pattern", student_id);
    $.ajax({
        url: url,
    })
        .done(function (data) {
            var subjects = JSON.parse(data);
            $(subjects).each(function (index, el) {
                var option =
                    "<option value='" +
                    el.id +
                    "'>" +
                    el.id +
                    " - " +
                    el.name +
                    "</option>";
                $("#subject_id").append(option);
            });
            $("#load").empty();
            console.log("success");
        })
        .fail(function () {
            console.log("error");
        })
        .always(function () {
            console.log("complete");
        });
});

$(".delete").click(function () {
    var form = $("#modalDeletingConfirmation form");
    $(form).attr("action", $(this).attr("url"));
    $("#modalDeletingConfirmation").modal("show");
});
