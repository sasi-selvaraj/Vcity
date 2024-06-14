// Notification
function alertNotify(type, icon, msg) {
    $.toast({
        heading: type,
        text: msg,
        icon: icon,
        stack: 1,
        position: "mid-center",
        hideAfter: 4000 ,
        
    });
}
function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function formatErrorMessage(jqXHR, exception) {
    if (jqXHR.status === 0) {
        return ('Not connected.\nPlease verify your network connection.');
    } else if (jqXHR.status == 404) {
        return ('The requested page not found.');
    } else if (jqXHR.status == 401) {
        return ('Sorry!! You session has expired. Please login to continue access.');
    } else if (jqXHR.status == 500) {
        return ('Internal Server Error.');
    } else if (exception === 'parsererror') {
        return ('Requested JSON parse failed.');
    } else if (exception === 'timeout') {
        return ('Time out error.');
    } else if (exception === 'abort') {
        return ('Ajax request aborted.');
    } else {
        return ('Unknown error occured. Please try again.');
    }
}

// Ajax header setup

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $(document).ajaxError(function (event, request, settings) { });
});

// Ajax
function ajaxResponse(route_url, method, data, successCallback) {
    $.ajax({
        url: route_url,
        method: method,
        data: data,
    }).done(function (data, textStatus, jqXHR) {
        // loadButton(".submit-btn");
        alertNotify("Success", "success", data.message)
        if (data.success == 1) {
            alertNotify("Success", "success", data.message);
            if (typeof successCallback === 'function') {
                successCallback(data);
            }
            var datatable = $(".table.dataTable").attr('id');
            window.LaravelDataTables[datatable].draw();
            if ($("body").hasClass("modal-open")) {
                $(".modal").modal("hide");
                $(".modal form").get(0).reset();
            }
        } else if (Array.isArray(data.message)) {
            $.each(data.message, function (index, error) {
                alertNotify("Notification", "error", error);
            });
        } else if (typeof data.message === 'object') {
            Object.values(data.message).forEach(errors => {
                if (Array.isArray(errors)) {
                    errors.forEach(error => alertNotify("Notification", "error", error));
                } else {
                    alertNotify("Notification", "error", errors);
                }
            });
        } else {
            alertNotify("Notification", "error", data.message);
        }        
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
        alertNotify("Error", "error", formatErrorMessage(jqXHR, errorThrown));
        // Request failed. Show error message to user. 
        // errorThrown has error message.
    });
}

function ajaxResponseRender(route_url, method, data, render, successCallback) {
    $.ajax({
        url: route_url,
        method: method,
        data: data,
        success: function (result) {
            $("#" + render).append(result);
            successCallback(result);
            $(".instructor-popover").popover({
                container: "body",
            });
        },
        error: function (data) {
            let errors = data.responseJSON.errors;
            for (let err in errors) {
                $.each(errors[err], function (index, error) {
                    // alertNotify("Error", "error", error);
                });
            }
        },
    });
}

// Funtion created for update with files, but files are no longer needed
function ajaxResponseFormData(route_url, method, data, successCallback) {
    $.ajax({
        url: route_url,
        method: method,
        data: data,
        processData: false,
        contentType: false, 
        mimeType: "multipart/form-data",
        dataType: "json",
    }).done(function (data, textStatus, jqXHR) {
        // loadButton(".submit-btn");
        alertNotify("Success", "success", data.message)
        if (data.success == 1) {
            alertNotify("Success", "success", data.message);
            if (typeof successCallback === 'function') {
                successCallback(data);
            }
            var datatable = $(".table.dataTable").attr('id');
            window.LaravelDataTables[datatable].draw();
            if ($("body").hasClass("modal-open")) {
                $(".modal").modal("hide");
                $(".modal form").get(0).reset();
            }
        } else if (Array.isArray(data.message)) {
            $.each(data.message, function (index, error) {
                alertNotify("Notification", "error", error);
            });
        } else if (typeof data.message === 'object') {
            Object.values(data.message).forEach(errors => {
                if (Array.isArray(errors)) {
                    errors.forEach(error => alertNotify("Notification", "error", error));
                } else {
                    alertNotify("Notification", "error", errors);
                }
            });
        } else {
            alertNotify("Notification", "error", data.message);
        } 
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
        alertNotify("Error", "error", formatErrorMessage(jqXHR, errorThrown));
        // Request failed. Show error message to user. 
        // errorThrown has error message.
    });
}

// button loader
function loadButton(element) {
    var element = $("" + element + "");
    var loadStatus = element.data("loading");
    if (loadStatus == "loading") {
        element.data("loading", "normal");
        element.html(element.data("text"));
        element.val(element.attr("text"));
        element.prop("disabled", false);
    } else {
        element.prop("disabled", true);
        element.data("text", element.html());
        element.attr("text", element.val());
        element.data("loading", "loading");
        element.html("Please Wait...");
        element.val("Please Wait...");
    }
}
$(document).ready(function(){
    $('.loading').hide();
    $('.loader').show();
});