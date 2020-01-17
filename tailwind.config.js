module.exports = {
    theme: {

        extend: {
            spacing: {
                28: '7rem',
                68: '17rem',
                80: '20rem',
                '3/4': '75%',
            },
            height: {
                80: '20rem',
            },
            fontFamily: {
                sans: ['"Noto Sans"', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', '"Helvetica Neue"', 'Arial', 'sans-serif', '"Apple Color Emoji"', '"Segoe UI Emoji"', '"Segoe UI Symbol"', '"Noto Color Emoji"']
            },
            fontSize: {
                'base': '1.0625rem',
                'xl': '1.1875rem',
                '5xl': '3.25rem',
            },
            boxShadow: {
                md: '5px 5px 7px rgba(0,0,0,.2)'
            },
            inset: {
              20: '5rem',
                40: '10rem',
            },
            colors: {
                black: '#232a34',
                'hms-navy': '#354066',
                'shady-blue': '#F1F8FF',
                'mild-yellow': '#FFF9E0',
                'cardy-blue': '#f5f9fa',
                'form-grey': '#f1f1f1',
                'border-grey': '#dcdcdc',
                'hard-ass-grey': '#a3a8b9',
                'sky-blue': '#91c8ff',
                'sunny-yellow': '#ffdf56',
                'teesav-blue': '#5a6282',
                'mustard': '#ffb40a',
                'opaque': 'rgba(255,255,255,.7)'

            }
        }
    },
    variants: {
        display: ['responsive', 'hover', 'focus', 'group-hover']
    },
    plugins: []
};
