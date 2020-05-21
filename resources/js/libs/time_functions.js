function intToTimeString(timeInt) {
    const padded_string = padToFour(timeInt);

    return `${padded_string[0]}${padded_string[1]}:${padded_string[2]}${
        padded_string[3]
    }`;
}

function padToFour(timeInt) {
    return `${timeInt}`.padStart(4, "0");
}

function dayNameFromInt(int) {
    const lookup = {
        0: "Sunday",
        1: "Monday",
        2: "Tuesday",
        3: "Wednesday",
        4: "Thursday",
        5: "Friday",
        6: "Saturday"
    };
    return lookup[int];
}

const everyHalfHour = [
    800,
    830,
    900,
    930,
    1000,
    1030,
    1100,
    1130,
    1200,
    1230,
    1300,
    1330,
    1400,
    1430,
    1500,
    1530,
    1600,
    1630,
    1700,
    1730,
    1800,
    1830,
    1900,
    1930,
    2000,
    2030,
    2100,
    2130,
    2200
];

function timeToMinutes(time) {
    const minutes = time % 100;
    const hours = Math.floor(time / 100);

    return hours * 60 + minutes;
}

function timeStringIsValid(time_string) {
    const regex = /^[012]{1}[0-9]{1}:[0-5]{1}[0-9]{1}$/;

    if (!time_string.match(regex)) {
        return false;
    }

    const hours = parseInt(time_string.slice(0, 2));

    return hours <= 23;
}

function dateAsYMDString(date) {
    const year = date.getFullYear();
    const month = date.getMonth() + 1;
    const day = date.getDate();
    return `${year}-${paddedToTwo(month)}-${paddedToTwo(day)}`;
}

function paddedToTwo(int) {
    if (int > 9) {
        return `${int}`;
    }

    return `0${int}`;
}

export {
    intToTimeString,
    dayNameFromInt,
    everyHalfHour,
    timeToMinutes,
    timeStringIsValid,
    dateAsYMDString
};
