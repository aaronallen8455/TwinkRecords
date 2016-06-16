/**
 * Created by Aaron Allen on 6/15/2016.
 */

/**
 * Get days in given month
 *
 * @param year
 * @param month
 * @returns {number}
 */
function daysInMonth(year, month) {
    return new Date(year, month, 0).getDate();
}

/**
 * Set the options for the day selector
 */
var setDayOptions = (function () {
    var weekDays = [
        'Sun',
        'Mon',
        'Tue',
        'Wed',
        'Thu',
        'Fri',
        'Sat'
    ];
    function dateSuffix(num) {
        var dateSuffix = [
            'th',
            'st',
            'nd',
            'rd',
            'th',
            'th',
            'th',
            'th',
            'th',
            'th'
        ];
        if (num === 12) return 'th';
        if (num === 11) return 'th';
        return dateSuffix[num.toString().slice(-1)];
    }
    return function (yearSelector, monthSelector, daySelector) {
        //empty out options
        while (daySelector.firstChild) {
            daySelector.removeChild(daySelector.firstChild);
        }
        for (var i=1; i<=daysInMonth(yearSelector.value, monthSelector.value); i++) {
            var option = document.createElement('option');
            option.value = i;
            var d = new Date(yearSelector.value, monthSelector.value-1, i);
            //Set option text to day name and number
            option.innerHTML = i + dateSuffix(i) + ' ' + weekDays[d.getDay()];
            //append to selector
            daySelector.appendChild(option);
        }
    }
})();

window.onload = function () {
    //get date selector elements
    var yearSelector = document.getElementsByName('year')[0];
    var monthSelector = document.getElementsByName('month')[0];
    var daySelector = document.getElementsByName('day')[0];

    //create initial options in day selector
    var currentDate = new Date();
    //set to current month if not persistent
    if (!monthSelector.dataset.value)
        monthSelector.value = currentDate.getMonth()+1;
    setDayOptions(yearSelector, monthSelector, daySelector);
    if (daySelector.dataset.value) {
        daySelector.value = daySelector.dataset.value;
    }else daySelector.value = currentDate.getDate();
    
    //set handler for change events
    yearSelector.onchange = monthSelector.onchange = setDayOptions.bind(null, yearSelector, monthSelector, daySelector);
};