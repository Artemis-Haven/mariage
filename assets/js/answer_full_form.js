import 'pretty-checkbox/src/pretty-checkbox.scss';

const $ = require('jquery');

$("[id$='absent']").change(function () {
    if ($(this).is(':checked')) {
        $(this).closest('.form-row').find("input[id$='attendCeremony'], input[id$='attendMeal'], input[id$='attendBrunch'], input[id$='veggie']").each(function () {
            $(this).prop("checked", false).prop("disabled", true);
        });
    } else {
        $(this).closest('.form-row').find("input[id$='attendCeremony'], input[id$='attendMeal'], input[id$='attendBrunch'], input[id$='veggie']").each(function () {
            $(this).prop("disabled", false);
        });
    }
}).change();

$("input[id$='attendCeremony'], input[id$='attendMeal'], input[id$='attendBrunch'], input[id$='veggie']").change(function () {
    if ($(this).closest('.form-row').find("input[id$='attendCeremony']:checked, input[id$='attendMeal']:checked, input[id$='attendBrunch']:checked, input[id$='veggie']:checked").length > 0) {
        $(this).closest('.form-row').find("[id$='absent']").each(function () {
            $(this).prop("checked", false).prop("disabled", true);
        });
    } else {
        $(this).closest('.form-row').find("[id$='absent']").each(function () {
            $(this).prop("disabled", false);
        });
    }
}).change();