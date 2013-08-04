<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
<style>
table tr td.connect {text-align:center}
span.link, .action {text-decoration: underline;}
.closed {text-decoration: line-through; color:#ccc;}
input.openclose {visibility: hidden;}
</style>
<script>
// "closed" toggle to gray-out, disable split
// "24-hours" set day hours to maximum 12:00am - 12:00am
// "24-7" set maximum hours on all days
// "repeat_column" sets hour, minute, am/pm to match monday
// "repeat_all" set all days to match monday
// "closed weekends" sets saturday and sunday to closed
// "M-F 9-5" sets those hours and runs "closed weekends"

$(document).ready(function(){
    $("#repeat_all").click(function(){
        repeat_all();
    });
    $("form").on("click", ".action.close", function(){
        //alert("Clicked close");
        this_day=$(this).parent().parent().parent().attr("id");
        //alert("Action to close " + this_day);
        close_day(this_day);
    });
    $("form").on("click", ".action.open", function(){
        //alert("Clicked close");
        this_day=$(this).parent().parent().parent().attr("id");
        //alert("Action to close " + this_day);
        open_day(this_day);
    });
    $("form").on("click", "input.openclose", function(){
        is_checked = $(this).prop('checked')
        clicked_day = $(this).parent().parent().attr("id")
        console.log(clicked_day)
        if(is_checked){
            open_day(clicked_day);
        }
        else{
            close_day(clicked_day);
        }
    });
    $("form").on("click", "span.action.allday", function(){
        set_day_24($(this).parent().parent().attr("id"));
    });
    $("#open_column").click(function(){
        repeat_open_column();
    });
    $("#close_column").click(function(){
        repeat_close_column();
    });
    $("span.action.twentyfourseven").click(function(){
        set_twentyfourseven();
    });
    $("span.action.closed_weekends").click(function(){
        closed_weekends();
    });
    $("span.action.default_hours").click(function(){
        set_defaults();
    });
    console.log(get_day_data("monday"));
    build_form();
});
function close_day(this_day){
    day_selector = $("#" + this_day);
    sub_text = '<span class="action open">open</span>'
    // Do what we need to do with data
    day_selector.children("td").children("input.openclose").prop('checked', false);
    // Then visually close the day
    day_selector.children("td").first().addClass("closed");
    // Switch the link from "close" to "open"
    day_selector.children("td").children("span.toggle.openclose").html(sub_text);
}
function open_day(this_day){
    day_selector = $("#" + this_day);
    sub_text = '<span class="action close">closed</span>';
    // Do what we need to do with data
    day_selector.children("td").children("input.openclose").prop('checked', true);
    // Then visually close the day
    day_selector.children("td").first().removeClass("closed");
    // Switch the link from "close" to "open"
    day_selector.children("td").children("span.toggle.openclose").html(sub_text);
}
function repeat_all(){
    monday_data = get_day_data("monday");
    console.log(monday_data);
    set_day_as("tuesday", monday_data);
    set_day_as("wednesday", monday_data);
    set_day_as("thursday", monday_data);
    set_day_as("friday", monday_data);
    set_day_as("saturday", monday_data);
    set_day_as("sunday", monday_data);
}
function repeat_open_column(){
    monday_data = get_day_data("monday");
    days = ["tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    length = days.length;
    for(var i = 0; i < length; i++){
        day_data = get_day_data(days[i]);
        day_data.hours.open = monday_data.hours.open;
        set_day_as(days[i], day_data);
    }
}
function repeat_close_column(){
    monday_data = get_day_data("monday");
    days = ["tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    length = days.length;
    for(var i = 0; i < length; i++){
        day_data = get_day_data(days[i]);
        day_data.hours.close = monday_data.hours.close;
        set_day_as(days[i], day_data);
    }
}
function set_day_24(day){
    day_selector = $("#" + day);
    day_selector.children("td").children("select.open.hour").val("12");
    day_selector.children("td").children("select.open.minute").val("00");
    day_selector.children("td").children("select.open.ampm").val("am");
    day_selector.children("td").children("select.close.hour").val("12");
    day_selector.children("td").children("select.close.minute").val("00");
    day_selector.children("td").children("select.close.ampm").val("am");
    open_day(day);
}
function set_twentyfourseven(){
    day_data = {
        "status":"open",
        "split":false,
        "hours":{
            "open":{
                "hour":"12",
                "minute":"00",
                "ampm":"am"
            },
            "close":{
                "hour":"12",
                "minute":"00",
                "ampm":"am"
            }
        }
    }
    days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    length = days.length;
    for(var i = 0; i < length; i++){
        set_day_as(days[i], day_data);
    }
}
function closed_weekends(){
    close_day("saturday");
    close_day("sunday");
}
function set_day_as(day, day_data){
    $("#" + day).children("td").children("select.open.hour").val(day_data.hours.open.hour);
    $("#" + day).children("td").children("select.open.minute").val(day_data.hours.open.minute);
    $("#" + day).children("td").children("select.open.ampm").val(day_data.hours.open.ampm);
    $("#" + day).children("td").children("select.close.hour").val(day_data.hours.close.hour);
    $("#" + day).children("td").children("select.close.minute").val(day_data.hours.close.minute);
    $("#" + day).children("td").children("select.close.ampm").val(day_data.hours.close.ampm);
    if(day_data.status == 'open'){
        open_day(day);
    }
    else{
        close_day(day);
    }
}
function get_day_data(day){
    status_checkbox = $("#" + day).children("td").children("input.openclose").prop('checked');
    if(status_checkbox){
        status = "open";
    }
    else {
        status = "closed";
    }
    open_hour = $("#" + day).children("td").children("select.open.hour").val();
    open_minute = $("#" + day).children("td").children("select.open.minute").val();
    open_ampm = $("#" + day).children("td").children("select.open.ampm").val();
    close_hour = $("#" + day).children("td").children("select.close.hour").val();
    close_minute = $("#" + day).children("td").children("select.close.minute").val();
    close_ampm = $("#" + day).children("td").children("select.close.ampm").val();
    data = {
        "status":status,
        "split":false,
        "hours":{
            "open":{
                "hour":open_hour,
                "minute":open_minute,
                "ampm":open_ampm
            },
            "close":{
                "hour":close_hour,
                "minute":close_minute,
                "ampm":close_ampm
            }
        }
    };
    return data;
}
function set_defaults(){
    days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    default_hours = get_default_hours();
    length = days.length;
    for(var j = 0; j < length; j++){
        //default_hours
        day_data = {
            "status":default_hours[days[j]]["status"],
            "split":false,
            "hours":{
                "open":{
                    "hour":default_hours[days[j]].hours[0].open.hour,
                    "minute":default_hours[days[j]].hours[0].open.minute,
                    "ampm":default_hours[days[j]].hours[0].open.ampm
                },
                "close":{
                    "hour":default_hours[days[j]].hours[0].close.hour,
                    "minute":default_hours[days[j]].hours[0].close.minute,
                    "ampm":default_hours[days[j]].hours[0].close.ampm
                }
            }
        }
        set_day_as(days[j], day_data);
    }
}
function get_string_for_day(day, capday){
    html_string = '<tr id="'+day+'">\
            <td><input class="openclose" type="checkbox" checked="true" name="'+day+'[status]" /> '+day+':</td><td>\
            <select class="open hour" name="'+day+'[open][hour]">\
    <option value="1">1</option>\
    <option value="2">2</option>\
    <option value="3">3</option>\
    <option value="4">4</option>\
    <option value="5">5</option>\
    <option value="6">6</option>\
    <option value="7">7</option>\
    <option value="8">8</option>\
    <option value="9">9</option>\
    <option value="10">10</option>\
    <option value="11">11</option>\
    <option value="12">12</option>\
    </select>\
    :\
    <select class="open minute" name="'+day+'[open][minute]">\
        <option value="00">00</option>\
        <option value="15">15</option>\
        <option value="30">30</option>\
        <option value="45">45</option>\
    </select>\
    <select class="open ampm" name="'+day+'[open][ampm]">\
        <option value="am">am</option>\
        <option value="pm">pm</option>\
    </select>\
    </td><td class="connect">to</td><td>\
    <select class="close hour" name="'+day+'[close][hour]">\
    <option value="1">1</option>\
    <option value="2">2</option>\
    <option value="3">3</option>\
    <option value="4">4</option>\
    <option value="5">5</option>\
    <option value="6">6</option>\
    <option value="7">7</option>\
    <option value="8">8</option>\
    <option value="9">9</option>\
    <option value="10">10</option>\
    <option value="11">11</option>\
    <option value="12">12</option>\
    </select>\
    :\
    <select class="close minute" name="'+day+'[close][minute]">\
        <option value="00">00</option>\
        <option value="15">15</option>\
        <option value="30">30</option>\
        <option value="45">45</option>\
    </select>\
    <select class="close ampm" name="'+day+'[close][ampm]">\
        <option value="am">am</option>\
        <option value="pm">pm</option>\
    </select>\
            </td>\
            <td><a class="action split" href="#">split</a> <span class="toggle openclose"><span class="action close">closed</span></span> <span class="action allday">24 hours</span></td>\
        </tr>';
    return html_string;
}
function build_form(){
    days=["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    console.log(days);
    length = days.length
    for(var i = 0; i < length; i++){
        console.log(days[i])
        $("table").append(get_string_for_day(days[i], "Monday"));
    }
    $("table").append('<tr><td colspan=3><input type="submit" name="submit" value="save"</td></tr');
    default_hours = get_default_hours();
    for(var j = 0; j < length; j++){
        //default_hours
        day_data={
            "status":default_hours[days[j]]["status"],
            "split":false,
            "hours":{
                "open":{
                    "hour":default_hours[days[j]].hours[0].open.hour,
                    "minute":default_hours[days[j]].hours[0].open.minute,
                    "ampm":default_hours[days[j]].hours[0].open.ampm
                },
                "close":{
                    "hour":default_hours[days[j]].hours[0].close.hour,
                    "minute":default_hours[days[j]].hours[0].close.minute,
                    "ampm":default_hours[days[j]].hours[0].close.ampm
                }
            }
        }
        set_day_as(days[j], day_data);
    }
}
function get_default_hours(){
    default_hours= {
        "twentyfourseven":false,
        "monday":{
            "status":"open",
            "split":false,
            "hours":{
                0:{
                    "open":{
                        "hour":"9",
                        "minute":"00",
                        "ampm":"am"
                    },
                    "close":{
                        "hour":"5",
                        "minute":"00",
                        "ampm":"pm"
                    }
                }
            }
        },
        "tuesday":{
            "status":"open",
            "split":false,
            "hours":{
                0:{
                    "open":{
                        "hour":"9",
                        "minute":"00",
                        "ampm":"am"
                    },
                    "close":{
                        "hour":"5",
                        "minute":"00",
                        "ampm":"pm"
                    }
                }
            }
        },
        "wednesday":{
            "status":"open",
            "split":false,
            "hours":{
                0:{
                    "open":{
                        "hour":"9",
                        "minute":"00",
                        "ampm":"am"
                    },
                    "close":{
                        "hour":"5",
                        "minute":"00",
                        "ampm":"pm"
                    }
                }
            }
        },
        "thursday":{
            "status":"open",
            "split":false,
            "hours":{
                0:{
                    "open":{
                        "hour":"9",
                        "minute":"00",
                        "ampm":"am"
                    },
                    "close":{
                        "hour":"5",
                        "minute":"00",
                        "ampm":"pm"
                    }
                }
            }
        },
        "friday":{
            "status":"open",
            "split":false,
            "hours":{
                0:{
                    "open":{
                        "hour":"9",
                        "minute":"00",
                        "ampm":"am"
                    },
                    "close":{
                        "hour":"5",
                        "minute":"00",
                        "ampm":"pm"
                    }
                }
            }
        },
        "saturday":{
            "status":"closed",
            "split":false,
            "hours":{
                0:{
                    "open":{
                        "hour":"9",
                        "minute":"00",
                        "ampm":"am"
                    },
                    "close":{
                        "hour":"5",
                        "minute":"00",
                        "ampm":"pm"
                    }
                }
            }
        },
        "sunday":{
            "status":"closed",
            "split":false,
            "hours":{
                0:{
                    "open":{
                        "hour":"9",
                        "minute":"00",
                        "ampm":"am"
                    },
                    "close":{
                        "hour":"5",
                        "minute":"00",
                        "ampm":"pm"
                    }
                }
            }
        }
    }
    return default_hours;
}
//alert("We open on Monday at " + default_hours.days.monday.hours[0].open);

</script>
</head>
<body>
<form action="datahandler.php" method="post">
    <table>
        <tr>
            <td><span class="link" id="repeat_all">repeat all</span></td><td><span id="open_column">repeat column</span></td><td style="width:25px; align: center;"></td><td><span id="close_column">repeat column</span></td>
        </tr>
        
    </table>
</form>
<span class="action twentyfourseven">24-7</span>   <span class="action closed_weekends">Closed Weekends</span>  <span class="action default_hours">Mon-Fri 9-5</span>
</body>