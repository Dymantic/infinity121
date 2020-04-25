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

export { intToTimeString, dayNameFromInt, everyHalfHour, timeToMinutes };
