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
    "8:00",
    "8:30",
    "9:00",
    "9:30",
    "10:00",
    "10:30",
    "11:00",
    "11:30",
    "12:00",
    "12:30",
    "13:00",
    "13:30",
    "14:00",
    "14:30",
    "15:00",
    "15:30",
    "16:00",
    "16:30",
    "17:00",
    "17:30",
    "18:00",
    "18:30",
    "19:00",
    "19:30",
    "20:00",
    "20:30",
    "21:00",
    "21:30",
    "22:00"
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

function stringTimeToInt(time_string) {
    return parseInt(time_string.replace(":", ""));
}

export {
    intToTimeString,
    dayNameFromInt,
    everyHalfHour,
    timeToMinutes,
    timeStringIsValid,
    dateAsYMDString,
    stringTimeToInt
};
