// ===================================================================
// <name of the app> – js/app.js
// ===================================================================

// ---- 1. configuration
const API = "api/";

// ---- 2. state
let state = {};

// ---- 3. communication with the server
function api(file, action, data, method)
{
    return $.ajax({
        url: API + file + "?action=" + action,
        method: method || "GET",
        data: data,
        dataType: "json"
    })
    .fail(function(xhr)
    {
        let message = xhr.responseJSON ? xhr.responseJSON.error : "The server cannot be reached";
        $("#message").text(message).show();
    });
}

// ---- 4. render functions
function showView(name)
{
    $(".view").removeClass("active");
    $("#view-" + name).addClass("active");
}

// ---- router
function route()
{
    let parts = location.hash.substring(1).split("/");
    showView(parts[0] || "home");
}

// ---- 5. events
function bindEvents()
{
    $(window).on("hashchange", route);
}

// ---- 6. start
$(document).ready(function()
{
    bindEvents();
    route();
});
