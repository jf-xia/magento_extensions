/**
 * Created by JetBrains PhpStorm.
 * User: alexander
 * Date: 18.06.12
 * Time: 13:20
 * To change this template use File | Settings | File Templates.
 */

function showMinutes(element) {
    hideMinuteElements();
    switch (element.value) {
        case "every":
            break;
        case "everyx":
            $("everyXMinutesDiv").show();
            break;
        case "specify":
            $("specifyMinutesDiv").show();
            $("specifyMinutes").className = "validate-select";
            break;
        case "fromto":
            $("fromToMinuteDiv").show();
            break;
    }
}

function hideMinuteElements() {
    $("everyXMinutesDiv").hide();
    $("specifyMinutesDiv").hide();
    $("specifyMinutes").className = "";
    $("fromToMinuteDiv").hide();
}

function showHours(element) {
    hideHourElements();
    switch (element.value) {
        case "every":
            break;
        case "everyx":
            $("everyXHoursDiv").show();
            break;
        case "specify":
            $("specifyHoursDiv").show();
            $("specifyHours").className = "validate-select";
            break;
        case "fromto":
            $("fromToHourDiv").show();
            break;
    }
}

function hideHourElements() {
    $("everyXHoursDiv").hide();
    $("specifyHoursDiv").hide();
    $("specifyHours").className = "";
    $("fromToHourDiv").hide();
}

function showDayOfMonth(element) {
    hideDayOfMonthElements();
    switch (element.value) {
        case 'every':
            break;
        case 'specify':
            $("specifyDayOfMonthDiv").show();
            $("specifyDayOfMonth").className = "validate-select";
            break;
        case 'fromto':
            $("fromToDayOfMonthDiv").show();
            break;
    }
}

function hideDayOfMonthElements() {
    $("specifyDayOfMonthDiv").hide();
    $("specifyDayOfMonth").className = "";
    $("fromToDayOfMonthDiv").hide();
}

function showMonth(element) {
    hideMonthElements();
    switch (element.value) {
        case 'every':
            break;
        case 'specify':
            $("specifyMonthDiv").show();
            $("specifyMonth").className = "validate-select";
            break;
        case 'fromto':
            $("fromToMonthDiv").show();
            break;
    }
}

function hideMonthElements() {
    $("specifyMonthDiv").hide();
    $("specifyMonth").className = "";
    $("fromToMonthDiv").hide();
}

function showDayOfWeek(element) {
    hideDayOfWeekElements();
    switch (element.value) {
        case 'every':
            break;
        case 'specify':
            $("specifyDayOfWeekDiv").show();
            $("specifyDayOfWeek").className = "validate-select";
            break;
        case 'fromto':
            $("fromToDayOfWeekDiv").show();
            break;
    }
}

function hideDayOfWeekElements() {
    $("specifyDayOfWeekDiv").hide();
    $("specifyDayOfWeek").className = "";
    $("fromToDayOfWeekDiv").hide();
}

function checkExpertMode() {
    var elementstohide = document.getElementsByClassName('hideonexpert');

    if ($('expert_mode').checked) {
        for (var i = 0; i < elementstohide.length; i++) {
            elementstohide.item(i).hide();
        }
        $('showonexpert').show();
    }
    else {
        $('showonexpert').hide();
        for (var i = 0; i < elementstohide.length; i++) {
            elementstohide.item(i).show();
        }
    }
}

function setExpertModeOn() {
    $('expert_mode').checked = true;
    var elementstohide = document.getElementsByClassName('hideonexpert');
    for (var i = 0; i < elementstohide.length; i++) {
        elementstohide.item(i).hide();
    }
    $('showonexpert').show();
}